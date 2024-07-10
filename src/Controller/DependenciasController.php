<?php

namespace App\Controller;

use App\Entity\InfoDependencia;
use App\Entity\InfoReparticion;
use App\Form\InfoDependenciaType;
use App\Repository\InfoDependenciaRepository;
use App\Repository\InfoReparticionRepository;
use App\Service\Actualizador;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/dependencias')]
class DependenciasController extends AbstractController
{
    #[Route('/', name: 'app_admin_dependencias_index', methods: ['GET'])]
    public function index(InfoReparticion $InfoReparticion, InfoDependenciaRepository $infoDependenciaRepository): Response
    {      
        $inforeparticion = $this->getUser()->getReparticion();
        return $this->render('admin_dependencias/index.html.twig', [            
            'info_dependencias' => $infoDependenciaRepository->findBy(['reparticion'=>$inforeparticion]),
        ]);
    }

    #[Route('/new', name: 'app_admin_dependencias_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InfoDependenciaRepository $infoDependenciaRepository,InfoReparticionRepository $InfoReparticionRepository): Response
    {
        $infoDependencia = new InfoDependencia();
        $now = new \DateTime();
        $user = $this->getUser();
        $form = $this->createForm(InfoDependenciaType::class, $infoDependencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $infoDependencia->setFechaCarga($now);    
            $infoDependencia->setFechaVigenciaDesde($now);    
            $infoDependencia->setFechaVigenciaHasta($now);    
            $infoDependencia->setReparticion($user->getReparticion());
            $infoDependencia->setUsuarioId($user->getId());  
            $infoDependenciaRepository->add($infoDependencia,true);           
            $reparticion = $user->getReparticion();
            $reparticion->addDependencia($infoDependencia);

            $InfoReparticionRepository->add($reparticion,true);

            return $this->redirectToRoute('app_admin_dependencias_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_dependencias/new.html.twig', [
            'info_dependencium' => $infoDependencia,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_dependencias_show', methods: ['GET'])]
    public function show(InfoDependencia $infoDependencia): Response
    {
        return $this->render('admin_dependencias/show.html.twig', [
            'info_dependencium' => $infoDependencia,
        ]);
    }

    #[Route('/{id}/editar', name: 'app_admin_dependencias_edit', methods: ['GET', 'POST'])]
    public function editar(Request $request, InfoDependencia $infoDependencia, InfoDependenciaRepository $repo_info_dependencia,
     Actualizador $actualizador): Response
    {
        $infoDependenciaOld = clone $infoDependencia;
        $form = $this->createForm(InfoDependenciaType::class, $infoDependencia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($infoDependenciaOld != $infoDependencia) {
                // Actualizar la entidad en la base de datos
                $actualizador->crearDependenciaHistorica($infoDependenciaOld);   
                $repo_info_dependencia->agregar($infoDependencia,true);
            }

            return $this->redirectToRoute('app_admin_dependencias_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_dependencias/edit.html.twig', [
            'info_dependencium' => $infoDependencia,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_dependencias_delete', methods: ['POST'])]
    public function delete(Request $request, InfoDependencia $infoDependencia, InfoDependenciaRepository $infoDependenciaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$infoDependencia->getId(), $request->request->get('_token'))) {
            $infoDependenciaRepository->remover($infoDependencia);    
        }

        return $this->redirectToRoute('app_admin_dependencias_index', [], Response::HTTP_SEE_OTHER);
    }
}


