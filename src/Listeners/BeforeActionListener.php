<?php
namespace App\Listeners;

use App\Service\RegistroActivacion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use App\Controller\BaseController;


 class BeforeActionListener {
    private $registroActivacion;

    public function __construct(RegistroActivacion $registroActivacion)
    {
        $this->registroActivacion = $registroActivacion;
    }

    /**
     * Esta metodo se ejecuta justo antes de la activacion de cada operacion 
     * invocada en un controller. Symfony provee por parametro el evento de activacion
     * de la operacion.
     * Se capturan todas las invocaciones y si el controller actual implementa el mentodo
     * 'registrarActivacion' se lo invoca.
     * 
     * @see \config\services.yaml
     * @param ControllerEvent $event
     * @return void
     */
    public function onController(ControllerEvent $event) {
        
        if(is_array($event->getController())) {            
            $controller = $event->getController()[0];            
        }            
        else $controller = $event->getController();
        if($controller) { 
            $class = new \ReflectionClass($controller);

            if($class->name == 'App\Controller\InicioController') return;
            if($class->hasMethod("registrarActivacion"))            
                $controller->registrarActivacion($this->registroActivacion);
        }
    }
}