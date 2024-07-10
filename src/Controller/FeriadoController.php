<?php

namespace App\Controller;

use Exception;
use App\Entity\Feriado;
use App\Form\FeriadoType;
use App\Repository\FeriadoRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/feriado')]
class FeriadoController extends BaseController
{

    public $repo_users;
    public $em;

    public function __construct(UsuarioRepository $usuarioRepository, EntityManagerInterface $entityManagerInterface) {
        $this->repo_users = $usuarioRepository;
        $this->em = $entityManagerInterface;
    }
    

    #[Route("/obtenerTodos", methods:["POST","GET"], name:"app_feriado_obtener_todos")]
    public function obtenerTodosLosFeriados( FeriadoRepository $feriadoRepository){
            $feriados= $feriadoRepository->findAll();

            return $this->render('feriado/index.html.twig', [
                'feriados' => $feriados
            ]);
    }

    #[Route("/obtenerPorAnioYMes", methods:["POST","GET"], name:"app_feriado_obtener_por_mes_anio")]
    public function obtenerFeriadosPorMesAnio(Request $request , FeriadoRepository $feriadoRepository){

        try{
            $mes= $request->get("mes");
            $anio= $request->get("anio");
            $feriados= $feriadoRepository->findBy(["mes" => $mes, "anio" => $anio]);
            //dd($feriados);
            return $this->json($feriados, Response::HTTP_ACCEPTED);
        }catch(Exception $e){
            return $this->json(["Error" => $e->getMessage()], Response::HTTP_CONFLICT);
        }
    }

    #[Route('/new', name: 'app_feriado_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FeriadoRepository $feriadoRepository): Response
    {
        $feriado = new Feriado();
        $form = $this->createForm(FeriadoType::class, $feriado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd("enviado");
            //dd($form->getData());
            $checkIfExists=$feriadoRepository->findOneBy(["dia" => $feriado->getDia(),"mes" => $feriado->getMes(),"anio" =>$feriado->getAnio()]);
            if(!$checkIfExists or empty($checkIfExists) or $checkIfExists == null){
                if(!$feriado->getDescripcion()||$feriado->getDescripcion() == ""){
                    $this->addFlash("error","Por favor introudzca una descripcion.");
                    return $this->redirectToRoute("app_feriado_new");
                }
                $feriadoRepository->agregar($feriado,true);
                $this->addFlash("success","El feriado fue creado con éxito.");
                return $this->redirectToRoute('app_feriado_obtener_todos', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash("error","Ya existe un feriado para la fecha seleccionada");
                return $this->redirectToRoute("app_feriado_new");
            }

        }

        return $this->render('feriado/new.html.twig', [
            'feriado' => $feriado,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_feriado_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Feriado $feriado, FeriadoRepository $feriadorepository, SessionInterface $session): Response
    {
        $form = $this->createForm(FeriadoType::class, $feriado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $feriadorepository->agregar($feriado,true);
            $this->addFlash('success', 'Los cambios sobre el feriado editado fueron guardados correctamente.');   
            return $this->redirectToRoute('app_feriado_obtener_todos', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('feriado/edit.html.twig', [
            'feriado' => $feriado,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_feriado_delete', methods: ["GET",'POST'])]
    public function delete(Request $request, Feriado $feriado, FeriadoRepository $feriadoRepository): Response
    {       
        if(in_array("ROLE_SUPER_ADMIN",$this->getUser()->getRoles())){
            $feriadoRepository->remover($feriado);
            $this->addFlash("success","Se borró el feriado correctamente"); 
        }
        return $this->redirectToRoute('app_feriado_obtener_todos', [], Response::HTTP_SEE_OTHER);
    }
}
