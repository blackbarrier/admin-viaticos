<?php

namespace App\Controller;

use App\Entity\InfoReparticion;
use App\Form\InfoReparticionType;
use App\Repository\DependenciaReparticionRepository;
use App\Repository\InfoDependenciaRepository;
use App\Repository\InfoReparticionRepository;
use App\Repository\ReparticionRepository;
use App\Service\Actualizador;
use DateTime;
use Exception;
use DateTimeZone;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/reparticion")] 
class ReparticionController extends BaseController{

    #[Route('/', name: 'app_info_reparticion_index', methods: ['GET'])]
    public function index(InfoReparticionRepository $infoReparticionRepository): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();
        if(in_array("ROLE_ADMIN", $roles)){
            $reparticionId = $user->getReparticion()->getId();
            return $this->redirectToRoute('app_info_reparticion_show', [
                "id" => $reparticionId,
            ], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('admin_reparticion/index.html.twig', [
            'info_reparticions' => $infoReparticionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_info_reparticion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InfoReparticionRepository $infoReparticionRepository): Response
    {
        $infoReparticion = new InfoReparticion();
        $form = $this->createForm(InfoReparticionType::class, $infoReparticion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoReparticionRepository->agregar($infoReparticion, true);
            return $this->redirectToRoute('app_info_reparticion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_reparticion/new.html.twig', [
            'info_reparticion' => $infoReparticion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_info_reparticion_show', methods: ['GET'])]
    public function show(InfoReparticion $infoReparticion): Response
    {
        $user = $this->getUser();
        $reparticion = $user->getReparticion();
        if (in_array("ROLE_ADMIN", $user->getRoles())){
            if ($infoReparticion != $reparticion){
                return $this->redirectToRoute('app_info_reparticion_show', ["id" => $reparticion->getId()], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin_reparticion/show.html.twig', [
            'info_reparticion' => $infoReparticion,
        ]);
    }

    #[Route('/{id}/dependencias', name: 'app_info_reparticion_dependencias', methods: ['GET'])]
    public function dependencias(InfoReparticion $infoReparticion, InfoDependenciaRepository $infoDependenciaRepository): Response
    {
        return $this->render('admin_dependencias/index.html.twig', [
            'info_dependencias' => $infoDependenciaRepository->findBy(['reparticion' => $infoReparticion]),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_info_reparticion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InfoReparticion $infoReparticion, InfoReparticionRepository $infoReparticionRepository, Actualizador $actualizador): Response
    {
        $infoReparticionOld = clone $infoReparticion;
        $user = $this->getUser();
        $reparticion = $user->getReparticion();
        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            if ($infoReparticion != $reparticion) {
                return $this->redirectToRoute('app_info_reparticion_index', ["id" => $reparticion->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        
        $form = $this->createForm(InfoReparticionType::class, $infoReparticion);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($infoReparticionOld != $infoReparticion) {                
                $actualizador->crearReparticionHistorica($infoReparticionOld);
                $infoReparticionRepository->agregar($infoReparticion, true);                
            }
            return $this->redirectToRoute('app_info_reparticion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_reparticion/edit.html.twig', [
            'info_reparticion' => $infoReparticion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_info_reparticion_delete', methods: ['POST'])]
    public function delete(Request $request, InfoReparticion $infoReparticion, InfoReparticionRepository $infoReparticionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $infoReparticion->getId(), $request->request->get('_token'))) {
            $infoReparticionRepository->remover($infoReparticion);
        }

        return $this->redirectToRoute('app_info_reparticion_index', [], Response::HTTP_SEE_OTHER);
    }


    // #[Route('/', name: 'app_admin_reparticion_show', methods: ['GET'])]
    // public function show(): Response
    // {
    //     $infoReparticion = $this->getUser()->getReparticion();
    //     // dd($infoReparticion);

    //     return $this->render('admin_reparticion/show.html.twig', [
    //         'info_reparticion' => $infoReparticion,
    //     ]);
    // }

    // #[Route('/edit', name: 'app_admin_reparticion_edit', methods: ['GET', 'POST'])]
    // public function edit(Request$request, EntityManagerInterface $entityManager): Response
    // {
    //     $infoReparticion = $this->getUser()->getReparticion();

    //     $form = $this->createForm(InfoReparticionType::class, $infoReparticion);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_admin_reparticion_show', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('admin_reparticion/edit.html.twig', [
    //         'infoReparticion' => $infoReparticion,
    //         'form' => $form,
    //     ]);
    // }

    #[Route("/obtenerDependencias",methods:["POST"],name:"app_reparticion_get_dependencia")]
    public function getDependenciaReparticion(ReparticionRepository $reparticionRepository, DependenciaReparticionRepository $dependenciaReparticionRepository):Response{
        $idReparticion= $_POST["idReparticion"];
        $reparticion = $reparticionRepository->findOneBy(["id" => $idReparticion]);
        if($reparticion){
            $relaciones= $dependenciaReparticionRepository->findBy(["reparticion" => $reparticion]);
            $dependencias = [];
            foreach ($relaciones as $relacion) {
                //$dependencias = $relacion->getDependencia();
                 $dependencias[]=[
                    "id" =>$relacion->getDependencia()->getId(),
                    "nombre" => $relacion->getDependencia()->getNombre(),
                ];
            }
            return $this->json($dependencias,Response::HTTP_ACCEPTED);
        }else{
            return $this->json(["error" => "No se encontró la reparticion con el id ".$idReparticion."."],Response::HTTP_CONFLICT);
        }
    }
}