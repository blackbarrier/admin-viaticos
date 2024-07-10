<?php
namespace App\Service;

use App\Entity\Actividad;
use App\Entity\Estado;
use App\Entity\TipoActividad;
use App\Repository\EstadoRepository;
use App\Repository\ActividadRepository;
use App\Repository\TipoActividadRepository;

class FlujoAdministrativo {
    
    public $grafo = [];
    public $rEstado;
    public $rActividad;
    public $rTipoActividad;

    function __construct( EstadoRepository $rEstado, ActividadRepository $rActividad, TipoActividadRepository $rTipoActividad) {
        $this->rEstado = $rEstado;
        $this->rActividad = $rActividad;
        $this->rTipoActividad = $rTipoActividad;
        $this->crearGrafoEstados();
    }

    private function crearGrafoEstados() {

        $item = new \stdClass();
        $item->origen = Estado::SOLICITADO;
        $item->destino = Estado::APROBADO;
        $item->accion = '';
        $item->validar = null;
        $this->grafo[] = $item;

        $item = new \stdClass();
        $item->origen = Estado::SOLICITADO;
        $item->destino = Estado::CADUCADO;
        $item->accion = '';
        $item->validar = null;
        $this->grafo[] = $item;

        $item = new \stdClass();
        $item->origen = Estado::APROBADO;
        $item->destino = Estado::CADUCADO;
        $item->accion = '';
        $item->validar = null;
        $this->grafo[] = $item;
        
        $item = new \stdClass();
        $item->origen = Estado::APROBADO;
        $item->destino = Estado::RENDIDO;
        $item->accion = '';
        $item->validar = function($viatico) {
            return !$viatico->getRendiciones()->isEmpty();
        };
        $this->grafo[] = $item;
    }

    /**
     * Retorna true si el viatico puede pasar del estado origen al destino
     * Validando lo prerequisitos.
     *
     * @param int $origen - id estado
     * @param int $destino - id estado
     * @param Viatico $viatico
     * @return void
     */
    public function puedePasarAEstado($origen, $destino, $viatico) {
        foreach($this->grafo as $item) {
            if($item->origen == $origen && $item->destino == $destino) {
                if($item->validar) return $item->validar($viatico);
                else return true;
            }            
        }
        return false;
    }
    
    /**
     * Retorna un conjunto de estados destino, validados
     *
     * @param int $origen - id estado
     * @param Viatico $viatico
     * @return Estado[] 
     */
    public function estadosDestinosValidadoPara($origen, $viatico) {
        $estados = [];
        $destino = null;
        foreach($this->grafo as $item) {
            if($item->origen == $origen) {
                $destino = $this->rEstado->findBy(['id' => $item->destino]);
                if($item->validar) {
                    if ($item->validar($viatico)) $estados[] = $destino;
                } else  $estados[] = $destino; 
            }            
        }
        return $estados;
    }

    /**
     * Retorna un conjunto de estados destino
     * sin validar.
     *
      * @param int $origen - id estado
     * @param Viatico $viatico
     * @return Estado[] 
     */
    public function estadosDestinoPara($origen, $viatico) {
        $estados = [];
        foreach($this->grafo as $item) {
            if($item->origen == $origen) {                
                $estados[] = $this->rEstado->findBy(['id' => $item->destino]);
            }            
        }
        return $estados;
    }


    /**
     * Registra una nueva transicion de estados
     *
     * @param Viatico $viatico
     * @param Estado $origen
     * @param Estado $destino
     * @param BaseController $controller
     * @param Request $request
     * @param Usuario $usuario
     * @return void
     */
    public function nuevaTransicion($viatico, $origen, $destino, $controller, $request, $usuario) {
        /**@var Request */
        //$request = $controller->get('request_stack')->getCurrentRequest();
        /**@var Router */
        //$router = $controller->get('router');
        /**@var User */
        //$user = $controller->getUser();
        $accion = $request->attributes->all()['_route'];
        $actividad = new Actividad();
        $tipoActividad = $this->rTipoActividad->findOneBy(["id" => TipoActividad::FLUJO_ADMINISTRATIVO]);
        $actividad->setTipo($tipoActividad);
        $actividad->setController(get_class($controller));
        $actividad->setAccion($accion);
        $actividad->setUsuario($usuario);
        $actividad->setFecha(new \DateTime("now"));
        $actividad->setEstadoOrigen($origen);
        $actividad->setEstadoDestino($destino);
        $actividad->setDetalles(["accion" => "Se Ingreso al metodo ".$actividad->getAccion()." del controller ".$actividad->getController()."."]); 
        $this->rActividad->add($actividad,true);
    }

}
