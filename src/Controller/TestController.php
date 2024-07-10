<?php

namespace App\Controller;

use App\Repository\PlantillaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Exception;

#[Route('/test')]
class TestController extends BaseController{

    #[Route("/repo", methods:["POST","GET"],name:"app_test_repoTest")]
    public function repotest(PlantillaRepository $plantillaRepository){
        dd($plantillaRepository->obtenerPlantillasConAgente(25));
    }
}
