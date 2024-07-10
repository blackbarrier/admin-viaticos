<?php

namespace App\Controller;

use Exception;
use Doctrine\ORM\Mapping\Entity;
use App\Service\RegistroActivacion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
;


class BaseController extends AbstractController { 


    /**
    * Notifica la activacion de una ruta.
    * Toma el nombre de la ruta como etiqueta.
    * Deriva el registro al servicio RegistroActivacion
    *
    * @return void
    */
    public function registrarActivacion($registroActivacion) {
    
        /**@var Request */
        $request = $this->container->get('request_stack')->getCurrentRequest();
        /**@var Router */
        $router = $this->container->get('router');
        /**@var User */
        $user = $this->getUser();
        $routeName = $request->getPathInfo();        
        $registroActivacion->nuevaAccion($this, $routeName, $user);
    }

   public function serializeEntity(Entity $entity){
        return json_encode($entity);
    }
    
}