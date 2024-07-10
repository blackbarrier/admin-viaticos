<?php

namespace App\Controller;


use App\Entity\Estado;
use App\Entity\Solicitud;
use DateTime;
use Exception;
use App\Entity\Viatico;
use App\Repository\AgenteRepository;
use App\Repository\EstadoRepository;
use App\Repository\ViaticoRepository;
use App\Repository\PlantillaRepository;
use App\Repository\SolicitudRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/solicitud')]
class SolicitudController extends BaseController{

    #[Route("/generar",  methods:["GET","POST"], name:"app_solicitud_generar_plantilla")]
    public function seleccionPlantilla(PlantillaRepository $plantillaRepository){
        if(!$this->getUser()){           
            return $this->redirectToRoute("app_login");
        }else{      
            try{
                $plantillas= $plantillaRepository->findBy(["borrado" => 0]);
                //dd($plantillas);
                if(empty($plantillas)){
                    $plantillas = null;
                }
            }catch( Exception $e){
                $this->addFlash("error","No se pudo generar la solicitud asociada a esa plantilla");
                return $this->redirectToRoute("app_solicitud_generar_plantilla");
                //dd($e);
            }   
            return $this->render("solicitud/plantillaGrid.html.twig",[
                "plantillas" => $plantillas,
            ]);
        }
    }

    #[Route("/solicitarDatos", methods:["GET","POST"], name:"app_solicitud_plantilla_ajax")]
    public function generarSolicitud(PlantillaRepository $plantillaRepository){
        $idPlantilla = $_POST["idPlantilla"];
        try{
            try{
                $plantilla = $plantillaRepository->findOneBy(["id" => $idPlantilla]);
                return $this->json($plantillaRepository->generarPlantillaEnBlanco_Json($plantilla),Response::HTTP_ACCEPTED);
            }catch(Exception $e){
                return $this->json($e,Response::HTTP_CONFLICT);
            }
        }catch(Exception $e){
            dd($e);
        }
    }

    #[Route("/altaViaticos", methods:["GET","POST"], name:"app_solicitud_alta_viaticos_ajax")]
    public function generarViaticos(PlantillaRepository $plantillaRepository, ViaticoRepository $viaticoRepository,
            AgenteRepository $agenteRepository, EstadoRepository $estadoRepository, SolicitudRepository $solicitudRepository){
       
        $viaticos= $_POST["viaticos"];
        $mes= $_POST["mes"];
        $anio= $_POST["anio"];
        $idPlantilla= $_POST["idPlantilla"];

        // dd($idPlantilla);

        $agregados= array();
        $operador = $this->getUser();
        $reparticion= $operador->getReparticion()->getNombre();
        $reparticionRef= $operador->getReparticion();
        if (in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            $plantilla = $plantillaRepository->findOneBy(["id" => $idPlantilla]) ;
            $dependencia =$plantilla->getDependencia()->getNombre();
            $dependenciaRef= $plantilla->getDependencia();
        }else{
            $dependencia = $operador->getDependencia()->getNombre();
            $dependenciaRef = $operador->getDependencia();
        }

        $estado = $estadoRepository->findOneBy(["id" => Estado::SOLICITADO]);
        $solicitud = new Solicitud();
        $solicitud->setFecha( new DateTime());
        $solicitud->setNroGdeba("TEST");
        $solicitud->setAgenteSolicitante($this->getUser());
        $solicitud->setMetodoRendicion("SIN DEFINIR");
        $solicitud->setEstado($estado);
        $solicitud->setAnio($anio);
        $solicitud->setMes($mes);
        $solicitud->setReparticion($reparticion);
        $importeTotal= 0;
        foreach ($viaticos as $item) {
           $current= $item;
           //dd($current);    
           $cuilAgente= $current["cuil"];
           $agente= $agenteRepository->findOneBy(["cuil" => $cuilAgente]);
           if($agente){                
                if($current["importe"] > 0){
                    try{
                        date_default_timezone_set("America/Argentina/Buenos_Aires");  
                        $viatico = new Viatico();
                        $viatico->setAgente($agente);
                        $viatico->setDias150($current["ciento_cincuenta"]);
                        $viatico->setDias100($current["cien"]);
                        $viatico->setDias70($current["setenta"]);
                        $viatico->setDias50($current["cincuenta"]);
                        $viatico->setDias40($current["cuarenta"]);
                        $viatico->setDias20($current["veinte"]);
                        $viatico->setDiasmov($current["movilidad"]);                        
                        $viatico->setEstado($estado);
                        $viatico->setModulo($current["modulo"]);
                        $viatico->setValorMovilidad($current["movilidad"]); //  <== mismo valor que el de los diasmov, por ahora (modificar)
                        $viatico->setFechaPedido(new DateTime());
                        $viatico->setOperador($operador);
                        $viatico->setImporte($current["importe"]);
                        $viatico->setCuil($agente->getCuil());
                        $viatico->setMes($mes);
                        $viatico->setAnio($anio);
                        $viatico->setReparticion($reparticion);
                        $viatico->setReparticionRefencia($reparticionRef);
                        $viatico->setDependencia($dependencia);
                        $viatico->setDependenciaReferencia($dependenciaRef);
                        //dd($viatico);
                        $viaticoRepository->agregar($viatico,true);
                        $solicitud->addViatico($viatico);
                        $current["dep"] = $agente->getDependencia()->getNombre();
                        $current["careg"] = $agente->getCategoria();
                        array_push($agregados,$current);
                        $importeTotal+= $current["importe"];
                    }catch(Exception $e){
                        //dd($e);
                        return $this->json($e);
                    }
                }                
           }           
        }
        if(!empty($agregados)){
            $solicitud->setImporteTotal($importeTotal);
            $solicitudRepository->agregar($solicitud,true);
            return $this->json($agregados, Response::HTTP_ACCEPTED);
        }else{
            return $this->json("No se agregaron viaticos.", Response::HTTP_CONTINUE);
        }
    }


   


}