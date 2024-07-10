<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class InicioController extends BaseController{

    /**
     * Obtiene las novedades para el usuario actual
     *
     */
    #[Route("/inicio", methods:["POST","GET"], name:"app_index")]
    public function index():Response{
        //dd($this->getUser()->getRoles());
        if(!$this->getUser()){           
            return $this->redirectToRoute("app_login");
        }else{
            $info_reparticion = $this->getUser()->getReparticion();       
            return $this->render("inicio.html.twig",[
                'info_reparticion' => $info_reparticion
            ]);
        }
    }
}