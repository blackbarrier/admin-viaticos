<?php

namespace App\Controller;

use App\Entity\Agente;
use App\Entity\Estado;
use App\Entity\Viatico;
use DateTime;
use Exception;
use DateTimeZone;
use App\Repository\AgenteRepository;
use App\Repository\DependenciaRepository;
use App\Repository\EstadoRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ViaticoRepository;
use App\Service\FlujoAdministrativo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;


#[Route("/viatico")]
class ViaticoController extends BaseController{

    /** @var ViaticoRepository */
    public $repo_viaticos;
    /** @var FlujoAdministrativo */
    public $flujo;

    public function __construct(ViaticoRepository $viaticoRepository, FlujoAdministrativo $flujo) {

        $this->repo_viaticos = $viaticoRepository;
        $this->flujo = $flujo;
    }
    
    /**
     * Obtiene las novedades para el usuario actual
     *
     * @param UsuarioRepository $usuarioRepository
     * @param ViaticoRepository $viaticoRepository
     * @return Response
     */
    #[Route("/novedades", name:"app_viatico_index")]
    public function index( UsuarioRepository $usuarioRepository, ViaticoRepository $viaticoRepository){
        //dd($this->getUser()->getRoles());
        if(!$this->getUser()){           
            return $this->redirectToRoute("app_login");
        }else{
            //si esta logueado guarda el ultimo acceso
            $currentUser= $usuarioRepository->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
            $timezone= new DateTimeZone('America/Argentina/Buenos_Aires');
            $hoy= new \DateTime("now",$timezone );
            $currentUser->setUltimoAcceso($hoy);
            $usuarioRepository->agregar($currentUser,true);
            $roles= $this->getUser()->getRoles();
            if(in_array("ROLE_ADMIN", $roles)){                
                $viaticos = $viaticoRepository->findby(["reparticionReferencia" => $this->getUser()->getReparticion()]);
            }else{     
                $viaticos = $viaticoRepository->findBy(["dependenciaReferencia" => $this->getUser()->getDependencia()]);               
            }
          
         
            return $this->render("viatico/index.html.twig",[
                "viaticos" => $viaticos
            ]);
        }
    }


    /**
     * Obtiene un viatico por id
     *
     * @param Request $request
     * @param ViaticoRepository $viaticoRepository
     * @return JsonResponse
     */
    #[Route("/ObtenerDatosViatico", methods:["POST","GET"], name:"app_get_data_viatico")]
    public function getDataViatico( Request $request, ViaticoRepository $viaticoRepository){

        try{
            $idViatico = $request->get("idViatico");
            $viatico = $viaticoRepository->findOneBy(["id" => $idViatico]);
            //dd($viatico);
            
            return $this->convertirJson($viatico);
        }catch(Exception $e){
            return $this->json([
                "error" => $e->getMessage()
            ], Response::HTTP_CONFLICT);
        }
        
    }


    /**
     * Hace los mismo que Controller->json(...)
     * pero atrapando las referencias circulares.
     *
     * @param mixed $data
     * @param integer $status
     * @param array $headers
     * @param array $context
     * @return JsonResponse
     */
    public function _json($data, int $status = 200, array $headers = [], array $context = []) {
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, string $format, array $context): string {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);        
        $json = $serializer->serialize($data, 'json', array_merge(
            ['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS], $context));            
        return new JsonResponse($json, $status, $headers, true);

    }

    /**
     * Hace los mismo que Controller->json(...)
     * pero atrapando las referencias circulares y transformando las 
     * fechas DateTime => string
     *
     * @param mixed $viaticos
     * @param integer $status
     * @param array $headers
     * @param array $context
     * @return JsonResponse
     */
    private function convertirJson($viaticos, int $status = 200, array $headers = [], array $context = []) {
        $encoder = new JsonEncoder();

        
        $dateCallback = function (object $innerObject, object $outerObject, string $attributeName, ?string $format = null, array $context = []): string {
            return $innerObject instanceof \DateTime ? $innerObject->format(\DateTime::ATOM) : '';
        };

        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, string $format, array $context): string {
                return $object->getId();
            },
            AbstractNormalizer::CALLBACKS => [
                'fechaAlta' => $dateCallback, // agente->fechaAlta
                'fechaAct' => $dateCallback, // agente->fechaAct
                'fechaPedido' => $dateCallback, // viatico->fechaPedido
                'fecha' => $dateCallback, // viatico->fechaPedido
            ],

        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $json = $serializer->serialize($viaticos, 'json', array_merge(
            ['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS], $context));

        return new JsonResponse($json, $status, $headers, true);

    }

    #[Route("/ObtenerViaticosMesAgente", methods:["POST","GET"], name:"app_get_agente_viaticos_mes")]
    public function getDataViaticosMesAgente( Request $request, ViaticoRepository $viaticoRepository, AgenteRepository $agenteRepository){

        try{
            $idAgente = $request->get("idAgente");
            $mes = $request->get("mes");
            $anio = $request->get("anio");
            $agente = $agenteRepository->findOneBy(["id" => $idAgente]);
            $viaticos = $viaticoRepository->ObtenerViaticosRendidosMes($agente,$mes,$anio);

            return $this->convertirJson($viaticos, Response::HTTP_ACCEPTED);
        } catch(Exception $e){            
            return $this->json([
                "error" => $e
            ], Response::HTTP_CONFLICT);
        }
        
    }

    /*
    #[Route("/contable", name: "app_contable_dependencias")]
    public function contar_viaticos(ViaticoRepository $viaticoRepository, DependenciaRepository $dependenciaRepository, DependenciaReparticionRepository $dependenciaReparticionRepository)
    {
        $result = array();
        $reparticion = $this->getUser()->getReparticion();
        $nombreReparticion = $this->getUser()->getReparticion()->getNombre();
        if(in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())){
            $dependencias =  $dependenciaRepository->findAll();
            foreach ($dependencias as $dependenciaR) {
                $viaticos = $viaticoRepository->findBy(["reparticion" => $nombreReparticion, "dependencia" => $dependenciaR->getNombre(), "estado" => Estado::SOLICITADO]);
                if (count($viaticos) > 0) {
                    array_push($result, [
                        "dependencia" => $dependenciaR->getNombre(),
                        "cantViaticos" => count($viaticos)
                    ]);
                }
            }
        }else{
            $reparticion = $this->getUser()->getReparticion();
            $nombreReparticion = $this->getUser()->getReparticion()->getNombre();
            $dependencias =  $dependenciaReparticionRepository->findBy(["reparticion" => $reparticion]);
            //dd($dependencias);
            foreach ($dependencias as $dependenciaR) {
                $dependencia = $dependenciaR->getDependencia();
                //dd($dependencia->getNombre());
                $viaticos = $viaticoRepository->findBy(["reparticion" => $nombreReparticion, "dependencia" => $dependencia->getNombre(), "estado" => Estado::SOLICITADO]);
               if(count($viaticos) >0 ){
                    array_push($result,[
                        "dependencia" => $dependencia->getNombre(),
                        "cantViaticos" => count($viaticos)
                    ]);
                }              
            }
        }

            return $this->render('viatico/contableCount.html.twig',[
            "viaticos" => $result,
        ]);
        
    }
    */

    #[Route("/contable", name: "app_contable_dependencias")]
    public function contar_viaticos(ViaticoRepository $viaticoRepository){
        
        $nombreReparticion = $this->getUser()->getReparticion()->getNombre();
        $conteos = $viaticoRepository->conteoViaticosPorEstadoYReparticion(Estado::SOLICITADO, $nombreReparticion);
        //dd($conteos);
        return $this->render('viatico/contableCount.html.twig',[
            "viaticos" => $conteos,
        ]);
    }


    #[Route("/contable/{dependencia}", name: "app_contable_index")]
    public function mostrar(ViaticoRepository $viaticoRepository, $dependencia) {
        // $dependencia = "Ejemplo";

        $viaticos = $viaticoRepository->findBy(["estado" => 1, "dependencia" =>$dependencia]);   
        return $this->render("viatico/contable.html.twig", [
            'viaticos' => $viaticos,
            'dependencia' => $dependencia            
        ]);   

    }


 
    #[Route("/contable/aprobar", name: "app_contable_aprobar")]
    public function aprobacion(Request $request, ViaticoRepository $viaticoRepository, EstadoRepository $estadoRepository){
        $autorizados = $request->request->get('arrayAutorizados');
        if($autorizados){
            $autorizados = Json_Decode($autorizados, True);    
            $anticipos = $request->request->get('arrayAnticipos');
            $anticipos = Json_Decode($anticipos, True);    
            $anticipos = array_intersect($autorizados, $anticipos);            
            foreach ($autorizados as $id) {
                $estadoAprobado = $estadoRepository->findOneBy(['id' => Estado::APROBADO]);
                $viatico = $viaticoRepository->findOneBy(["id" => intval($id)]);
                
                $viatico->setEstado($estadoAprobado); 
    
                if ( in_array($viatico->getId(), $anticipos)){
                    $viatico->setAnticipo(Viatico::ANTICIPO);   
                }else{
                    $viatico->setAnticipo(Viatico::RENDICION_FINAL);   
                }
                
                $viaticoRepository->agregar($viatico, true);
            }
            $this->addFlash("success","Viatico/s Aprobados con éxito.");
        }else{
            $this->addFlash("error","Hubo un error al intentar autorizar el/los viático/s");
        }
        return $this->redirectToRoute('app_contable_dependencias');
    }

    #[Route("contable/ajax_autorizar_contable",methods:["POST"],name:"contable_autorizar_viatico")]
    public function autorizarVContable(Request $request,ViaticoRepository $viaticoRepository, EstadoRepository $estadoRepository){
        $autorizados = $_POST["arrayViaticos"];
        $output=[];
        foreach($autorizados as $viaticoAuto){
           $estadoSiguiente = $estadoRepository->findOneBy(["id" => Estado::APROBADO]); 
           $viatico = $viaticoRepository->findOneBy(["id" => $viaticoAuto["id"]]);

           if($viatico && $viatico != null && !empty($viatico)) {
                $viatico->setAnticipo($viaticoAuto["anticipo"]);
                
                $estadoPrevio = $viatico->getEstado();
                $viatico->setEstado($estadoSiguiente);                                
                $viaticoRepository->agregar($viatico,true);
                $this->flujo->nuevaTransicion($viatico, $estadoPrevio, $estadoSiguiente, $this, $request, $this->getUser());
                
                $output= ["success" => "El viático con id = ".$viaticoAuto["id"]." fue aprobado"];
           }
        }   
        $this->addFlash("success","Se autorizaron los viáticos seleccionados");   
        return $this->json($output,Response::HTTP_ACCEPTED);        
    }

    //Rutas para admin/////////

    #[Route("/admin-viaticos", name: "app_admin_viaticos")]
    public function indexViaticos(ViaticoRepository $viaticoRepository, AgenteRepository $agenteRepository, Request $request):Response
    {
        $userReparticion = $this->getUser()->getReparticion();
        $nombreReparticion = $userReparticion->getNombre();
        $viaticosReparticion = $viaticoRepository->findBy(['reparticion' => $nombreReparticion]);

        $idViatico = $request->request->get('idViatico');
        $cuilAgente = $request->request->get('cuilAgente');  

        if ($idViatico !=null or $cuilAgente!=null){
            $viaticosReparticion = $viaticoRepository->findBy(['reparticion' => $nombreReparticion,'id' => $idViatico]);
            if ($viaticosReparticion == null){
                $agente = $agenteRepository->findBy(['cuil' => $cuilAgente]);   
                $viaticosReparticion = $viaticoRepository->findBy(['reparticion' => $nombreReparticion,'agente' => $agente]);
            }
        }


        return $this->render("admin_viatico/index.html.twig", [
            "viaticos" => $viaticosReparticion            
        ]);

    }

    #[Route("admin/viaticos_agente/{id}", name: "app_admin_viaticos_agente")]
    public function viaticosAgente(Agente $agente, ViaticoRepository $viaticoRepository)
    {
        $viaticos = $viaticoRepository->findBy(['agente' => $agente]);    
        
        // dd($viaticos);

        return $this->render("admin_viatico/viaticoAgente.html.twig", [
            "viaticos" => $viaticos,
            "agente" => $agente->getNombre()." ".$agente->getApellido()
        ]);

    }


    #[Route("admin/cambiar_estado/{id}", name: "app_admin_cambiar_estado")]
    public function cambiarEstado()
    {
        dd("En construccion");
    }

    #[Route("admin/caducar/{id}", name: "app_admin_caducar")]
    public function eliminar()
    {
        dd("En construccion");
    }

}