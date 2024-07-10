<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Rendicion;
use App\Entity\Estado;
use App\Repository\UsuarioRepository;
use App\Repository\ViaticoRepository;
use App\Service\FlujoAdministrativo;
use App\Repository\DependenciaRepository;
use App\Repository\EstadoRepository;
use App\Repository\RendicionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/rendicion")]
class RendicionController extends BaseController{

    /** @var UsuarioRepository */
    public $repo_users;    
    /** @var  FlujoAdministrativo */
    public $flujo;
    /** @var RendicionRepository */
    public $repo_rendiciones;

    public function __construct(UsuarioRepository $usuarioRepository, RendicionRepository $rendicionRepository, FlujoAdministrativo $flujo) {
        $this->repo_users = $usuarioRepository;
        $this->repo_rendiciones = $rendicionRepository;
        $this->flujo = $flujo;
    }
    
    #[Route("/rendir/{idViatico}", methods:["POST","GET"], defaults:["idViatico" => null], name:"app_calendario_rendicion_test")]
    public function rendicionCalendario($idViatico, DependenciaRepository $dependenciaRepository, ViaticoRepository $viaticoRepository){
            $viatico = $viaticoRepository->findOneBy(["id" => $idViatico]);
            $dependencias = $dependenciaRepository->findAll();
            //dd($viatico);
            return $this->render("rendicion/calendario.html.twig",[
                "dependencias" => $dependencias,
                "viatico" => $viatico,
            ]);
        
    }

    #[Route("/guardar", methods:["POST"], name:"app_rendicion_guardar")]
    public function guardarRendicion(Request $request, ViaticoRepository $viaticoRepository, RendicionRepository $rendicionRepository, EstadoRepository $estadoRepository){
        $solicitud = json_decode($_POST["solicitado"],true);
        $rendiciones = json_decode($_POST["rendicion"],true);
        try{
            $viatico = $viaticoRepository->findOneBy(["id" => $solicitud["id"]]);
            foreach ($rendiciones as $rendicion) {
                $rend = new Rendicion();
                $fecha= new DateTime($rendicion["fecha"]);
                $rend->setFecha($fecha);
                $rend->setDestino($rendicion["destino"]);
                $rend->setModulo($rendicion["modulo"]);
                $rend->setPorcentaje($rendicion["porcentaje"]);
                $rend->setViatico($viatico);
                $rendicionRepository->agregar($rend,true);
            }
            $siguiente = $estadoRepository->findOneBy(["id" => Estado::RENDIDO]);            
            $anterior = $viatico->getEstado();
            $viatico->setEstado($siguiente);
            $this->flujo->nuevaTransicion($viatico, $anterior, $siguiente, $this, $request, $this->getUser());
            $viaticoRepository->agregar($viatico, true);
            $this->addFlash("success","Se registraron las rendiciones correctamente");
            return $this->redirectToRoute("app_viatico_index");
        }catch(Exception $e){
            $this->addFlash("error","Hubo un error al registrar las rendiciones");
            return $this->redirectToRoute("app_viatico_index");
            //dd($e);
        }
    }


   

}