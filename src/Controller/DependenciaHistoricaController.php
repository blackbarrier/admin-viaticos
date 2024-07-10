<?php

namespace App\Controller;
use Exception;
use App\Repository\InfoDependenciaHistoricaRepository;
use App\Repository\UsuarioRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/dependencia-Historica')]
class DependenciaHistoricaController extends BaseController{    
    
   #[Route('/', name: 'app_dependnecia_historica_index', methods: ['GET'])]
    public function index(InfoDependenciaHistoricaRepository $repo_dep_historica): Response {      
        $dependencias = $repo_dep_historica->findAll();
        return $this->render('depenedenciaHistorica/index.html.twig', [
            'dependencias' => $dependencias
        ]);
    }
    
    #[Route('/{id}/detalles/ver', name: 'app_dependnecia_historica_detalles', methods: ["GET","POST"] )]
    public function verDetalles(int $id , InfoDependenciaHistoricaRepository $repo_dep_historica, UsuarioRepository $repo_users ): Response {      
        $dependencia = $repo_dep_historica->find($id);
        $usuarioCarga = $repo_users->findOneBy(["id" => $dependencia->getUsuarioId()]) ;
        return $this->render('depenedenciaHistorica/show.html.twig', [
            'dependencia' => $dependencia,
            "usuarioCarga" => $usuarioCarga
        ]);
    }
}