<?php

namespace App\Controller;

use App\Entity\Plantilla;
use App\Form\PlantillaType;
use App\Repository\AgenteRepository;
use App\Repository\DependenciaReparticionRepository;
use App\Repository\DependenciaRepository;
use App\Repository\PlantillaRepository;
use App\Repository\ReparticionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

#[Route('/plantilla')]
class PlantillaController extends BaseController
{
    #[Route('/', name: 'app_plantilla_index', methods: ['GET'])]
    public function index(PlantillaRepository $plantillaRepository, ReparticionRepository $reparticionRepository, DependenciaRepository $dependenciaRepository): Response
    {
        if(in_array("ROLE_ADMIN",$this->getUser()->getRoles())){
            $reparticion = $reparticionRepository->findOneBy(["id" => $this->getUser()->getReparticion()->getId()]);            
            $plantillas = $plantillaRepository->findBy(["reparticion" =>$reparticion, "borrado" => False]);
        }else{            
            $dependencia = $dependenciaRepository->findOneBy(["id" => $this->getUser()->getDependencia()->getId()]);            
            $plantillas = $plantillaRepository->findBy(["dependencia" => $dependencia, "borrado" => False]);
        } 
        return $this->render('plantilla/index.html.twig', [
            'plantillas' => $plantillas,
        ]);
    }

    #[Route('/new', name: 'app_plantilla_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlantillaRepository $plantillaRepository, AgenteRepository $agenteRepository, DependenciaRepository $dependenciaRepository, DependenciaReparticionRepository $dependenciaReparticionRepository): Response
    {
        $agentes = $agenteRepository->findBy(["borrado" => 0]);        
        $plantilla = new Plantilla();
        $form = $this->createForm(PlantillaType::class, $plantilla);
        $form->handleRequest($request);
        if(in_array("ROLE_ADMIN",$this->getUser()->getRoles())){
            $admin = true;
            $dependencias = $dependenciaReparticionRepository->obtenerporReparticion($this->getUser()->getReparticion());
            //$dependencias = $dependenciaRepository->findBy(["reparticion"=> ,"tipoDependenciaId" => [4,5,6]]);
        } else{
            $admin = false;
            $dependencias = null;
        }

        
        if ($form->isSubmitted() && $form->isValid()) {
            
            date_default_timezone_set("America/Argentina/Buenos_Aires");  
            $plantilla->setFechaCreacion(new DateTime());
            $plantilla->setultimaMod(new DateTime());
            $plantilla->setBorrado(False);
            
            $jsonAgente = $_POST['plantilla']["agentes"];
            $arrayAgente = json_decode($jsonAgente);
           
            if ($arrayAgente!=null){            
                $arrayAgente = array_map('intval', $arrayAgente);
                foreach ($arrayAgente as $agente_id) {
                    $plantilla->addAgente($agenteRepository->find($agente_id));
                };
                if($admin){
                    $plantilla->setReparticion($this->getUser()->getReparticion());
                    $dataDependenciaId = $request->get("form_dependencia");
                    $dependenciaManual= $dependenciaRepository->findOneBy(["id" => $dataDependenciaId]);
                    $plantilla->setDependencia($dependenciaManual);
                }else{
                    $plantilla->setReparticion($this->getUser()->getReparticion());
                    $plantilla->setDependencia($this->getUser()->getDependencia());
                }
                $plantillaRepository->agregar($plantilla, true);
                $this->addFlash("success","Se registró la nueva plantilla con éxito.");
            }else{
                $this->addFlash("error","Hubo un error al obtener los agentes de la plantilla");
            }
            return $this->redirectToRoute('app_plantilla_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plantilla/new.html.twig', [
            // 'plantilla' => $plantilla,
            'agentes' => $agentes,
            'form' => $form,
            'dependencias' => $dependencias
        ]);
    }

    #[Route('/{id}', name: 'app_plantilla_show', methods: ['GET'])]
    public function show(Plantilla $plantilla, AgenteRepository $agenteRepository): Response
    {
        $agentes = $plantilla->getAgentes();


        return $this->render('plantilla/show.html.twig', [
            'agentes' => $agentes,
            'plantilla' => $plantilla,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_plantilla_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,AgenteRepository $agenteRepository, Plantilla $plantilla, PlantillaRepository $plantillaRepository): Response
    {

        
        $agentes = $agenteRepository->findAll();
        $agentesActuales = $plantilla->getAgentes();
       

        $form = $this->createForm(PlantillaType::class, $plantilla);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $plantilla->setultimaMod(new DateTime());
            foreach($agentesActuales as $agente){
                $plantilla->removeAgente($agente);
            };

            $jsonAgente = $_POST['plantilla']["agentes"];
            $arrayAgente = json_decode($jsonAgente);
            if ($arrayAgente != null) { 
                $arrayAgente = array_map('intval', $arrayAgente);
                // dd($arrayAgente);

                foreach ($arrayAgente as $agente_id) {
                    $plantilla->addAgente($agenteRepository->find($agente_id));
                };

                $plantillaRepository->agregar($plantilla, true);
                $this->addFlash("success","La platnilla fue modificada con éxito");
            }else{
                $this->addFlash("error","Hubo un error al intentar recuperar los agentes de la plantilla.");
            }

            return $this->redirectToRoute('app_plantilla_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('plantilla/edit.html.twig', [
            'agentes' => $agentes,
            'agentesActuales' => $agentesActuales,
            'plantilla' => $plantilla,
            'form' => $form,
            'prueba' => "asd",
        ]);
    }

    #[Route('/{id}', name: 'app_plantilla_delete', methods: ['POST'])]
    public function delete(Request $request, Plantilla $plantilla, PlantillaRepository $plantillaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $plantilla->getId(), $request->request->get('_token'))) {
            $plantilla->setBorrado(True);
            $plantillaRepository->remover($plantilla);
            $this->addFlash("success","La plantilla fue borrada con éxito.");
        }

        return $this->redirectToRoute('app_plantilla_index', [], Response::HTTP_SEE_OTHER);
    }


}

