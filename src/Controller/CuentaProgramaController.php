<?php
namespace App\Controller;

use App\Controller\BaseController;
use App\Repository\CuentaProgramaRepository;
use App\Repository\ReparticionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/cuenta_programa')]
class CuentaProgramaController extends BaseController {
    
    /**
     * Obtiene las cuentas para el usuario actual
     *
     * @param ReparticionRepository $rReparticion
     * @param CuentaProgramaRepository $rCuentaPrograma
     * @return Response
     */
    #[Route("/index", name:"app_cprograma_index")]
    public function index( ReparticionRepository $rReparticion,CuentaProgramaRepository $rCuentaPrograma) {
        //dd($this->getUser()->getRoles());
        if(!$this->getUser()){
            return $this->redirectToRoute("app_login");
        } else {
            $cuentas = $rCuentaPrograma->findby(["reparticion" => $this->getUser()->getReparticion()]);
            return $this->render("programas/index.html.twig",[
                "cuentas" => $cuentas
            ]);
        }
    }
    
    
}

