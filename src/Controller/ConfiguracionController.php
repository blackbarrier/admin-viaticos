<?php

namespace App\Controller;

use App\Entity\Configuracion;
use App\Form\ConfiguracionType;
use App\Repository\ConfiguracionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Exception;
#[Route('/configuracion')]
class ConfiguracionController extends BaseController
{
    #[Route('/index', name: 'app_configuracion_index')]
    
    public function new_index(ConfiguracionRepository $rConfiguracion): Response {   
        // lista los parametros activos
        $dataParametro = $rConfiguracion->findBy(['borrado' => 0]);

        return $this->render('configuracion/index.html.twig',
        ['parametros' => $dataParametro]);
        
    }

    #[Route('/nuevo', name: 'configuracion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConfiguracionRepository $configuracionRepository) : Response {  
        $configuracion = new Configuracion();
        $form = $this->createForm(ConfiguracionType::class, $configuracion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!$configuracion->getNombre() || $configuracion->getNombre() == "" || !$configuracion->getValor() || $configuracion->getValor() == ""|| $configuracion->getLeyenda() == ""){
                $this->addFlash("error","Error al cargar el parámetro, se enviaron datos vacíos");
                return $this->redirectToRoute("configuracion_new");
            }else{
                try{
                    $check = $configuracionRepository->findOneBy(["nombre" => $configuracion->getNombre(),"borrado" => 0]);
                    if($check || !empty($check)){
                        $this->addFlash("error","Ya existe una variable de configuración con ese nombre");
                        return $this->redirectToRoute('app_configuracion_index');
                    }else{
                        $configuracion->setBorrado(0);
                        $configuracionRepository->add($configuracion);
                        $this->addFlash('success', "Guardado");
                        return $this->redirectToRoute('app_configuracion_index');
                    }
                }catch(Exception $e){
                    $this->addFlash('error', "No se guardo correctamente. <br>|| ".$e->getMessage());
                return $this->redirectToRoute('app_configuracion_index');
                }           
            }
        }
        return $this->render('configuracion/new.html.twig', [
            'configuracion' => $configuracion,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route("/{id}/edit", name:"configuracion_edit",methods: ["GET", "POST"], requirements: ["id" => "\d+"])]

    public function edit(Request $request, Configuracion $configuracion = null, ConfiguracionRepository $rConfiguracion): Response {
        if (!$configuracion) {
            return $this->redirectToRoute('usuario');   
        }
        $form = $this->createForm(ConfiguracionType::class, $configuracion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($rConfiguracion->guardar($configuracion)) {
                $this->addFlash('success', "Guardado");
                return $this->redirectToRoute('app_configuracion_index');
            }
            $this->addFlash('error', "No se guardo correctamente.");
            return $this->redirectToRoute('app_configuracion_index');
            

        }
        return $this->render('configuracion/edit.html.twig', [
            'configuracion' => $configuracion,
            'form' => $form->createView(),
        ]);
    }

    
    #[Route("/eliminar/{id}", name:"configuracion_eliminar", methods:["GET", "POST"])]
    
    public function delete(Request $request, Configuracion $configuracion, ConfiguracionRepository $rConfiguracion) {        
        $configuracion->setBorrado(1);
        $rConfiguracion->guardar($configuracion);
        $this->addFlash('success', "Se borró el parámetro seleccionado.");
        return $this->redirectToRoute('app_configuracion_index');        
    }


}
