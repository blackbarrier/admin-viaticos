<?php
namespace App\Service;


use App\Entity\Usuario;
use App\Entity\Actividad;
use App\Entity\TipoActividad;
use App\Controller\BaseController;
use App\Repository\ActividadRepository;
use App\Repository\TipoActividadRepository;

/**
 * Esta clase es la encargada de recibir la notificacion de la 
 * activacion de cada metodo en los controllers que heredan de BaseController.
 */
class RegistroActivacion {
    private $repo_actividad;
    private $repo_tipo_actividad;
    public function __construct(ActividadRepository $actividadRepository, TipoActividadRepository $tipoActividadRepository) {
        $this->repo_actividad = $actividadRepository;
        $this->repo_tipo_actividad= $tipoActividadRepository;
        date_default_timezone_set('America/Argentina/Buenos_Aires');
    }

    /**
     * Una activacion de un metodo en un conreoller ha sucedido.
     *
     * @param BaseController $controller
     * @param string $nombreAccion - nombre o etiqueta de la accion
     * @param Usuario|null $usuario
     * @return void
     */
    public function nuevaAccion(BaseController $controller, string $nombreAccion, ?Usuario $usuario, ) {
        if(!$usuario){
            return;
        }
        $actividad= new Actividad();
        $tipoActividad = $this->repo_tipo_actividad->findOneBy(["id" => TipoActividad::ACTIVIDAD_GRAL]);
        $actividad->setTipo($tipoActividad);
        $actividad->setController(get_class($controller));
        $actividad->setAccion($nombreAccion);
        $actividad->setUsuario($usuario);
        $actividad->setFecha(new \DateTime("now"));
        $actividad->setDetalles(["accion" => "Se Ingreso al metodo ".$actividad->getAccion()." del controller ".$actividad->getController()."."]); 
        $this->repo_actividad->add($actividad,true);       
     }
    
    

}
