<?php

namespace App\Controller;
use Exception;
use App\Entity\Agente;
use App\Form\AgenteType;
use App\Repository\AgenteRepository;
use App\Repository\DependenciaReparticionRepository;
use App\Repository\DependenciaRepository;
use App\Repository\PlantillaRepository;
use App\Repository\ReparticionRepository;
use App\Repository\RolRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/agente')]
class AgenteController extends BaseController{    
    
   #[Route('/', name: 'app_agente_index', methods: ['GET'], defaults: ['system_action' => 'app_agente_index'])]
    public function index(AgenteRepository $agenteRepository, RolRepository $rolRepository): Response
    {      

        $rolesUser = $this->getUser()->getRoles();
        //dd($rolesUser);
        $superadmin = $rolRepository->findOneBy(["nombre" => "ROLE_ADMIN"]);
        if(in_array($superadmin->getNombre(),$rolesUser)){
            $user_reparticion = $this->getUser()->getReparticion();
            $agentes = $agenteRepository->findBy(["borrado" => 0, "reparticion" => $user_reparticion]);
        }
        // else{
        //     $user_dependencia = $this->getUser()->getDependencia();
        //     $agentes = $agenteRepository->findBy(["borrado" => 0, "dependencia" => $user_dependencia]);
        // }
        return $this->render('agente/index.html.twig', [
            'agentes' => $agentes
        ]);
    }

    #[Route('/new', name: 'app_agente_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AgenteRepository $agenteRepository, 
    DependenciaReparticionRepository $dependenciaReparticionRepository, DependenciaRepository $dependenciaRepository, ReparticionRepository $reparticionRepository){ 
        date_default_timezone_set("America/Argentina/Buenos_Aires");  
        $now = new \DateTime();    
        $agente = new Agente();

        // obtiene las dependencias disponibles del usuario admin que crea agentes
        $relaciones= $dependenciaReparticionRepository->findBy(["reparticion" => $this->getUser()->getReparticion()]);
        $dependencias = [];
        foreach ($relaciones as $relacion) {
            //$dependencias = $relacion->getDependencia();
                $dependencias[]=[
                "id" =>$relacion->getDependencia()->getId(),
                "nombre" => $relacion->getDependencia()->getNombre(),
            ];
        }
        // $dependencias = $dependenciaRepository->findAll();
        $reparticiones = $reparticionRepository->findAll();
        //
        $form = $this->createForm(AgenteType::class, $agente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $idDependencia = $_POST["dependencias"];
            // $idreparticion = $_POST["reparticiones"];
            $agenteExistente = $agenteRepository->findOneBy(['cuil' => $agente->getCuil()]); 
            if ($agenteExistente!= null) {     
                if ($agenteExistente->getActivo() == 1 ){                        
                    $this->addFlash('error', 'Error! Ya existe un agente activo con el cuil indicado.');  
                    return $this->redirectToRoute('app_agente_new');
                }else{
                    $this->addFlash('error', 'Error! Ya existe el agente. Debe activarlo.');
                    return $this->redirectToRoute('app_agente_new');
                }                           
            }     
            $existeAgenteConDni =  $agenteRepository->findOneBy(['numeroDocumento' => $agente->getNumeroDocumento()]); 
            if($existeAgenteConDni!=null){
                if ($existeAgenteConDni->getActivo() == 1 ){                        
                    $this->addFlash('error', 'Error! Ya existe un agente activo con el número de documento provisto.');  
                    return $this->redirectToRoute('app_agente_new');
                }else{
                    $this->addFlash('error', 'Error! Ya existe el agente. Debe activarlo.');
                    return $this->redirectToRoute('app_agente_new');
                }       
            }         
            $nombre=strtoupper($agente->getNombre());
            $agente->setNombre($nombre);
            $apellido=strtoupper($agente->getApellido());
            $agente->setApellido($apellido);
            $agente->setFechaAlta($now);
            $agente->setActivo(1);
            $agente->setBorrado(0);
            $agente->setFechaAct($now);
            $agente->setReparticion($this->getUser()->getReParticion());
            $dependencia = $dependenciaRepository->findOneBy(["id" => $idDependencia]);
            $agente->setDependencia($dependencia);
            
            if (strpos($agente->getCuil(), $agente->getNumeroDocumento()) !== false) {
                $agenteRepository->agregar($agente, true);
                $this->addFlash("success","El nuevo agente fue registrado con éxito");                
                return $this->redirectToRoute('app_agente_index');
            } else {
                $this->addFlash("error", "Campo DNI y campo CUIL deben ser coincidentes.");
                return $this->redirectToRoute('app_agente_new');
            }

        }
        return $this->render('agente/new.html.twig', [
            'agente' => $agente,
            'reparticiones' => $reparticiones,
            "dependencias" => $dependencias,
            "dependenciaAgente" => null,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_agente_show', methods: ['GET'])]
    public function show(Agente $agente): Response
    {
        return $this->render('agente/show.html.twig', [
            'agente' => $agente,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_agente_edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, Agente $agente, AgenteRepository $agenteRepository, 
    DependenciaRepository $dependenciaRepository, DependenciaReparticionRepository $dependenciaReparticionRepository, ReparticionRepository $reparticionRepository): Response
    {
        date_default_timezone_set("America/Argentina/Buenos_Aires");  
        $agenteExistente = $agente;
        $now = new \DateTime();     
        //obtiene las dependencias disponibles del usuario admin que crea agentes
        $relaciones= $dependenciaReparticionRepository->findBy(["reparticion" => $this->getUser()->getReparticion()]);
        $dependencias = [];
        foreach ($relaciones as $relacion) {
            //$dependencias = $relacion->getDependencia();
                $dependencias[]=[
                "id" =>$relacion->getDependencia()->getId(),
                "nombre" => $relacion->getDependencia()->getNombre(),
            ];
        }
        $reparticiones = $reparticionRepository->findAll();
        //      
        $form = $this->createForm(AgenteType::class, $agente);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {  
            if($agente->getExterno() == 1){
                if(!$agente->getReparticion()){
                    $idReparticionNueva = $_POST["reparticiones"];
                    $agente->setReparticion($reparticionRepository->findOneBy(["id" => $idReparticionNueva]));
                }
            }else{
                $agente->setReparticion($this->getUser()->getReparticion());
            }
            if(isset($_POST["dependencias"])){
                $idDependencia= $_POST["dependencias"];        
                $dependencia = $dependenciaRepository->findOneBy(["id" => $idDependencia]);
                $agente->setDependencia($dependencia);
            }
            $nombre=strtoupper($agente->getNombre());
            $agente->setNombre($nombre);
            $apellido=strtoupper($agente->getApellido());
            $agente->setApellido($apellido);
            $agente->setFechaAct($now);
            
            if ($agenteExistente != null) {
                if ($agenteExistente->getActivo() == 1) {
                    if (strpos($agente->getCuil(), $agente->getNumeroDocumento()) !== false) {
                        //si el dni esta contenido en el cuil, continua
                        if(trim($agente->getNumeroDocumento()) != $agenteExistente->getNumeroDocumento()){
                            //cambio el documento
                            $existeAgenteConDni =  $agenteRepository->findOneBy(['numeroDocumento' => $agente->getNumeroDocumento()]); 
                            if($existeAgenteConDni!=null){
                                if($agente->getId() != $existeAgenteConDni->getId()){
                                    //si no son el mismo agente, da error e interrumpe la ejecución                                
                                    if ($existeAgenteConDni->getActivo() == 1 ){                        
                                        $this->addFlash('error', 'Error! Ya existe un agente activo con el número de documento provisto.');  
                                        return $this->redirectToRoute('app_agente_edit',["id" =>$agenteExistente->getId()]);
                                    }else{
                                        $this->addFlash('error', 'Error! Ya existe el agente on el número de documento provisto, pero está inactivo.');
                                        return $this->redirectToRoute('app_agente_edit',["id" =>$agenteExistente->getId()]);
                                    }     
                                }                             
                            }    
                        }
                             
                        //
                        if($agente->getCuil() != $agenteExistente->getCuil()){
                            //cambio el cuil, busco por un agente ya existente con el cuil, si no esta , se puede continuar
                            $agentePreexistente = $agenteRepository->findOneBy(['cuil' => $agente->getCuil()]); 
                            if ($agentePreexistente != null) { 
                                //si encuentra uno, da error, e interrumpe la ejecucion, devolviendo un valor 
                                if ($agentePreexistente->getActivo() == 1 ){                        
                                    $this->addFlash('error', 'Error! Ya existe un agente activo con el cuil indicado.');  
                                    return $this->redirectToRoute('app_agente_edit',["id" =>$agenteExistente->getId()]);
                                }else{
                                    $this->addFlash('error', 'Error! Ya existe el agente con el cuil provisto, pero esta inactivo.');
                                    return $this->redirectToRoute('app_agente_edit',["id" =>$agenteExistente->getId()]);
                                }                           
                            }     
                        }                       
                        $agenteRepository->agregar($agente, true);
                        $this->addFlash('success', 'El agente ha sido editado correctamente.');
                        return $this->redirectToRoute("app_agente_index",[]);
                    }else {
                        $this->addFlash("error", "Campo DNI y campo CUIL deben ser coincidentes.");
                        return $this->redirectToRoute("app_agente_edit", ["id" => $id]);
                    }      
                } else {
                    $this->addFlash('error', ' El agente se encuentra inactivo, debe activarlo para continuar.');
                    return $this->redirectToRoute('app_agente_edit', ["id" => $id]);
                }
            }else{
                $this->addFlash('error', 'Error! No se pudo traer los datos del agente');
                return $this->redirectToRoute('app_agente_index', []);
            }                     


        }

        return $this->render('agente/edit.html.twig', [
            'agente' => $agente,
            'reparticiones' => $reparticiones,
            "dependencias" => $dependencias,
            "dependenciaAgente" => $agenteExistente->getDependencia()->getId(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/activar-desactivar', name: 'app_agente_cambiar_activo', methods: ['GET', 'POST'])]
    public function cambiar_activo(Request $request, Agente $agente, AgenteRepository $agenteRepository, PlantillaRepository $plantillaRepository): Response {

            $plantillas = $plantillaRepository->findAll();
            foreach ($plantillas as $plantilla) {
               if( $plantilla->getAgentes()->contains($agente)){
                    $plantilla->removeAgente($agente);
                    $plantillaRepository->agregar($plantilla,true);
                };
            };   
            $now = new \DateTime();       
            $activo = $agente->getActivo();
           
            switch($activo){
                case 0:
                    $agente->setActivo(1);
                    $operacion = "activó";
                break;
                case 1:
                    $agente->setActivo(0);
                    $operacion = "desactivó";
                break;
            }
            $agente->setFechaAct($now);           
            $agenteRepository->agregar($agente, true);
            $this->addFlash("success","Se ".$operacion." el agente con éxito.");
            return $this->redirectToRoute('app_agente_index', [], Response::HTTP_SEE_OTHER);
       
    }
    

    #[Route('/{id}/eliminar', name: 'agente_eliminar',  methods: ['GET', 'POST'])]
    public function delete(Request $request, Agente $agente, AgenteRepository $agenteRepository): Response
    {  
        //dd("pase de largo");
        $now = new \DateTime();   
        // $agente->setBorrado(1);
        $agente->setFechaAct($now);        
  
        $agenteRepository->agregar($agente, true);
        return $this->redirectToRoute('app_agente_index', [], Response::HTTP_SEE_OTHER);
      
    }

    #[Route('/getAgentesViaticos', name: "app_get_agentes_ajax", methods: ['GET', 'POST'])]
    public function getAgentes(Request $request, AgenteRepository $agenteRepository)
    {
        $q = $_POST["q"];
        $mode= $_POST["mode"];
        switch($mode){
            case 0:
                $agentes = $agenteRepository->buscarAgentes($q);
            break;
            case 1:
                $user_reparticion = $this->getUser()->getReparticion()->getId();
                $agentes = $agenteRepository->buscarAgentesReparticion($q, $user_reparticion);
                // dd($agentes);
            break;
        }
    
        $filteredData = [];
        foreach ($agentes as $agente) {
            $text = strtoupper($agente->getLegajo() . " " . $agente->getApellido() . " " . $agente->getNombre());
            
            // Check if the search term is found in the agent's details
            if (stripos($text, $q) !== false) {
                $filteredData[] = [
                    "id" => $agente->getId(),
                    "text" => $text,
                ];
            }
        }
    
        // Return the filtered data as JSON response
        return $this->json($filteredData, Response::HTTP_ACCEPTED);
        
    }
}
