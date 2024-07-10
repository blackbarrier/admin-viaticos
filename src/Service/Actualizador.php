<?php

namespace App\Service;

use App\Entity\Reparticion;
use App\Entity\Dependencia;
use App\Entity\InfoReparticion;
use App\Entity\InfoDependencia;
use App\Entity\InfoReparticionHistorica;
use App\Entity\InfoDependenciaHistorica;
use App\Repository\InfoReparticionRepository;
use App\Repository\InfoDependenciaRepository;
use App\Repository\InfoReparticionHistoricaRepository;
use App\Repository\InfoDependenciaHistoricaRepository;
use Exception;

class Actualizador{

    private $repo_dependencias_internas;
    private $repo_dependencias_historicas;
    private $repo_reparticiones_internas;
    private $repo_reparticiones_historicas;

    function __construct(InfoDependenciaRepository $infoDependenciaRepository, InfoDependenciaHistoricaRepository $infoDependenciaHistoricaRepository,
    InfoReparticionRepository $infoReparticionRepository, InfoReparticionHistoricaRepository $infoReparticionHistoricaRepository) {
        $this->repo_dependencias_historicas = $infoDependenciaHistoricaRepository;
        $this->repo_dependencias_internas = $infoDependenciaRepository;
        $this->repo_reparticiones_historicas= $infoReparticionHistoricaRepository;
        $this->repo_reparticiones_internas= $infoReparticionRepository;
    }


    /**
     * Retorna Verdadero si la reparticion existe y fue cambiada
     * SOLO DEBE USARSE CON REPARTICIONES INTERNAS (DEL REGISTRO)
     *
     * @param Reparticion $reparticion
     * @return bool
     */
    public function verificarReparticion(Reparticion $reparticion){
        if($reparticion){
            $condicion = false;
            $resultadoBusqueda = $this->repo_reparticiones_internas->findOneBy(["reparticionReferencia" => $reparticion]);
            if($resultadoBusqueda != null || !empty($resultadoBusqueda)){
                //si encuentra algo sigue y evalua            
               $condicion= $this->comprobarCambiosReparticion($resultadoBusqueda,$reparticion);
            }else{
                //si no encuentra nada devuelve verdadero, porque no la teiene registrada
                $condicion= true;
            }
        }else{
            //si la repartición es externa va a retornar falso, a este servicio no le compete
            // verificar las externas, solo las internas
           $condicion= false; 
        }
        return  $condicion;
    }
    

     /**
     * Retorna Verdadero si la dependencia existe y fue cambiada
     * SOLO DEBE USARSE CON DEPENDENCIAS INTERNAS (DEL REGISTRO)
     *
     * @param Dependencia $dependencia
     * @return bool
     */
    public function verificarDependencia( Dependencia $dependencia){
        if($dependencia){
            $condicion = false;
                $resultadoBusqueda = $this->repo_dependencias_internas->findOneBy(["dependenciaReferencia" => $dependencia]);
                if($resultadoBusqueda != null || !empty($resultadoBusqueda)){
                    //si encuentra algo sigue y evalua            
                    $condicion= $this->comprobarCambiosDependencia($resultadoBusqueda,$dependencia);
                }else{
                    //si no encuentra nada devuelve verdadero, porque no la teiene registrada
                    $condicion= true;
                }
            }else{
                //si la repartición es externa va a retornar falso, a este servicio no le compete
                // verificar las externas, solo las internas
               $condicion= false; 
            }
            return  $condicion;
    }

    /**
     * Compara una InfoReparticion con una Reparticion en sus campos clave, si la InfoReparticion 
     * coincide con todos los campos vitales de la Reparticion, devuelve false.
     * Si hay cambios retorna true.
     *
     * @param InfoReparticion $reparticionInterna
     * @param Reparticion $reparticion
     * @return bool
     */
    public function comprobarCambiosReparticion(InfoReparticion $reparticionInterna, Reparticion $reparticion){
        $campos = [
            'nombre',
            'abreviatura',
            'cuit',
            'rotulo',
            'sucursal',
            'numeroCuenta',
            'digito',
            'cbu',
            'estadoReparticionId'
        ];
        $outcome = false;
        foreach ($campos as $campo) {            
            if ($reparticionInterna->{'get' . ucfirst($campo)}() !== $reparticion->{'get' . ucfirst($campo)}()) {
                $outcome = true;
            }else{
                $outcome = false;
            }
        }
        return $outcome;
    }

    public function actualizarDependenciaManual(InfoDependencia $infoDependencia){
        $original = $this->repo_dependencias_internas->find($infoDependencia->getId());
        dd($infoDependencia,$original);
    }

     /**
     * Compara una InfoDependencia con una Dependencia en sus campos clave, si la InfoDependencia 
     * coincide con todos los campos vitales de la Dependencia, devuelve false.
     * Si hay cambios retorna true.
     *
     * @param InfoDependencia $dependenciaInterna
     * @param Dependencia $dependencia
     * @return bool
     */
    public function comprobarCambiosDependencia(InfoDependencia $dependenciaInterna, Dependencia $dependencia){
        $campos = [
            'nombre',
            'abreviatura',
            'dependenciaRenaperId',
            'codigoGdeba',
            'tipoDependenciaId',
            'tipoDelegacion',
            'descripcion',
            'clase',
            'partidoId',
            'fechaVigenciaDesde',
            'fechaVigenciaHasta',
            'fechaCarga',
            'usuarioId',
            'estadoDependenciaId'
        ];
        $outcome= false;
        foreach ($campos as $campo) {
            if( getType($dependenciaInterna->{'get' . ucfirst($campo)}()) == "object"){
                $valor1=$dependenciaInterna->{'get' . ucfirst($campo)}()->format("Y-m-d");
                $valor2=$dependencia->{'get' . ucfirst($campo)}()->format("Y-m-d");
                //var_dump($valor1,$valor2);
                //die();              
               if($valor1 == $valor2){
                $outcome= true;
               }else{
                $outcome = false;
               }
            }else{
                if ($dependenciaInterna->{'get' . ucfirst($campo)}() !== $dependencia->{'get' . ucfirst($campo)}()) {    
                    $outcome= true;               
                }else{                    
                    $outcome = false;
                    //dd("campo de conflicto: ".$campo." , valor sistema:[".$dependenciaInterna->{'get' . ucfirst($campo)}()."] valor vista:[".$dependencia->{'get' . ucfirst($campo)}()."]");
                }
            }
        }
       if($outcome){
        echo "true";
       }
        return $outcome;
    
    }

    
    
    /**
     * Crea una nueva isntancia de dependencia histórica a base de una dependencna (infoDependencia) existente.
     * Básicamente es un clon de la dependencia que se pasa por parametro pero en otra tabla, y referenciando a 
     * la dependencia original en el campo "Dependencia de referencia" y la persiste.
     * 
     * @param InfoDependencia $dependencia
     * 
     * @return void|mixed
     */
    public function crearDependenciaHistorica(InfoDependencia $dependencia){
        if($dependencia){
           
                $dependenciaReferencia = $this->repo_dependencias_internas->find($dependencia->getId());
                //dd($dependencia,$dependenciaReferencia);
                $dependenciaHistorica = new InfoDependenciaHistorica();
                $dependenciaHistorica->setReparticion($dependencia->getReparticion());
                $dependenciaHistorica->setDependenciaReferencia($dependenciaReferencia);
                $dependenciaHistorica->setNombre($dependencia->getNombre());
                $dependenciaHistorica->setAbreviatura($dependencia->getAbreviatura());
                $dependenciaHistorica->setDependenciaRenaperId($dependencia->getDependenciaRenaperId());
                $dependenciaHistorica->setCodigoGdeba($dependencia->getCodigoGdeba());
                $dependenciaHistorica->setTipoDependenciaId($dependencia->getTipoDependenciaId());
                $dependenciaHistorica->setTipoDelegacion($dependencia->getTipoDelegacion());
                $dependenciaHistorica->setDescripcion($dependencia->getDescripcion());
                $dependenciaHistorica->setClase($dependencia->getClase());
                $dependenciaHistorica->setPartidoId($dependencia->getPartidoId());
                $dependenciaHistorica->setFechaVigenciaDesde($dependencia->getFechaVigenciaDesde());
                $dependenciaHistorica->setFechaVigenciaHasta($dependenciaHistorica->getFechaVigenciaHasta());
                $dependenciaHistorica->setFechaCarga($dependencia->getFechaCarga());
                $dependenciaHistorica->setUsuarioId($dependencia->getUsuarioId());
                $dependenciaHistorica->setEstadoDependenciaId($dependencia->getEstadoDependenciaId());
                $dependenciaHistorica->setExterna($dependencia->isExterna());
                $this->repo_dependencias_historicas->add($dependenciaHistorica,true);
        }
    }

    /**
     * Crea una nueva isntancia de repartición histórica a base de una repartición (InfoReparticion) existente.
     * Básicamente es un clon de la repartición que se pasa por parametro pero en otra tabla, y referenciando a 
     * la repartición original en el campo "Repartición de referencia"
     * 
     * @param InfoReparticion $infoReparticion
     * 
     * @return void|mixed
     */
    public function crearReparticionHistorica(InfoReparticion $infoReparticion){
        if($infoReparticion){           
                $reparticionReferencia= $this->repo_reparticiones_internas->find($infoReparticion->getId());
                $reparticionHistorica = new InfoReparticionHistorica();
                $reparticionHistorica->setReparticionReferencia($reparticionReferencia);
                $reparticionHistorica->setNombre($infoReparticion->getNombre());
                $reparticionHistorica->setAbreviatura($infoReparticion->getAbreviatura());
                $reparticionHistorica->setCuit($infoReparticion->getCuit());
                $reparticionHistorica->setRotulo($infoReparticion->getRotulo());
                $reparticionHistorica->setSucursal($infoReparticion->getSucursal());
                $reparticionHistorica->setNumeroCuenta($infoReparticion->getNumeroCuenta());
                $reparticionHistorica->setDigito($infoReparticion->getDigito());
                $reparticionHistorica->setCbu($infoReparticion->getCbu());
                $reparticionHistorica->setEstadoReparticionId($infoReparticion->getEstadoReparticionId());
                $reparticionHistorica->setExterna($infoReparticion->isExterna());

                $this->repo_reparticiones_historicas->add($reparticionHistorica,true);           
        }
    }

    /**
     * Asigna los valores claves de una Reparticion a una InfoReparticion y los persiste
     *
     * @param InfoReparticion $infoReparticion
     * @param Reparticion $reparticion
     * @return void
     */
    public function importarDatosReparticion(InfoReparticion $infoReparticion, Reparticion $reparticion){
        $infoReparticion->setReparticionReferencia($reparticion);
        $infoReparticion->setNombre($reparticion->getNombre());
        $infoReparticion->setAbreviatura($reparticion->getAbreviatura());
        $infoReparticion->setCuit($reparticion->getCuit());
        $infoReparticion->setRotulo($reparticion->getRotulo());
        $infoReparticion->setSucursal($reparticion->getSucursal());
        $infoReparticion->setNumeroCuenta($reparticion->getNumeroCuenta());
        $infoReparticion->setDigito($reparticion->getDigito());
        $infoReparticion->setCbu($reparticion->getCbu());
        $infoReparticion->setEstadoReparticionId($reparticion->getEstadoReparticionId());

        $this->repo_reparticiones_internas->add($infoReparticion,true);
    }

    /**
     * Asigna los valores claves de una Dependencia a una InfoDependencia y los persiste
     *
     * @param InfoDependencia $infoDependencia
     * @param Dependencia $dependencia
     * @return void
     */
    public function importarDatosDependencia(InfoDependencia $infoDependencia, Dependencia $dependencia){
        $infoDependencia->setDependenciaReferencia($dependencia);
        $infoDependencia->setNombre($dependencia->getNombre());
        $infoDependencia->setAbreviatura($dependencia->getAbreviatura());
        $infoDependencia->setDependenciaRenaperId($dependencia->getDependenciaRenaperId());
        $infoDependencia->setCodigoGdeba($dependencia->getCodigoGdeba());
        $infoDependencia->setTipoDependenciaId($dependencia->getTipoDependenciaId());
        $infoDependencia->setTipoDelegacion($dependencia->getTipoDelegacion());
        $infoDependencia->setDescripcion($dependencia->getDescripcion());
        $infoDependencia->setClase($dependencia->getClase());
        $infoDependencia->setPartidoId($dependencia->getPartidoId());
        $infoDependencia->setFechaVigenciaDesde($dependencia->getFechaVigenciaDesde());
        $infoDependencia->setFechaVigenciaHasta($dependencia->getFechaVigenciaHasta());
        $infoDependencia->setFechaCarga($dependencia->getFechaCarga());
        $infoDependencia->setUsuarioId($dependencia->getUsuarioId());
        $infoDependencia->setEstadoDependenciaId($dependencia->getEstadoDependenciaId());

        $this->repo_dependencias_internas->add($infoDependencia,true);
    }

    /**
     * Crea una reparticion histórica (InfoReparticionHistórica) a base de la reparticion provista, y, luego de persistir la misma, 
     * obtiene la InfoReparticion a la que la Reparticion corresponde y procede a actualizar sus datos y persistir los cambios.
     * Este Proceso no modifica la reparticion original (vista) , solo su InfoReparticion en sistema,
     * para su óptimo uso, este método debe ejecutarse luego del método "verificarReparticion"
     *
     * @param Reparticion $reparticion
     * @return void|mixed
     */
    public function actualizarReparticion(Reparticion $reparticion){
        try{
            $infoReparticion = $this->repo_reparticiones_internas->findOneBy(["reparticionReferencia" => $reparticion]);
            if($infoReparticion){
                $this->crearReparticionHistorica($infoReparticion);
                $this->importarDatosReparticion($infoReparticion,$reparticion);
            }else{
                 //si no existe,solo la crea y la persiste
                 $infoReparticion = new InfoReparticion();
                 $this->importarDatosReparticion($infoReparticion,$reparticion);
            }
        }catch(Exception $e){
            dd($e->getMessage());
        }
    }

     /**
     * Crea una dependencia histórica (InfoDependenciaHistórica) a base de la dependencia provista, y, luego de persistir la misma, 
     * obtiene la InfoDependencia a la que la Dependencia corresponde y procede a actualizar sus datos y persistir los cambios.
     * Este Proceso no modifica la dependencia original (vista) , solo su InfoDependencia en sistema,
     * para su óptimo uso, este método debe ejecutarse luego del método "verificarDependencia"
     *
     * @param Dependencia $dependencia
     * @return void|mixed
     */
    public function actualizarDependencia(Dependencia $dependencia){
        try{
            $infoDependencia = $this->repo_dependencias_internas->findOneBy(["dependenciaReferencia" => $dependencia]);
            if($infoDependencia){
                $this->crearDependenciaHistórica($infoDependencia);
                $this->importarDatosDependencia($infoDependencia,$dependencia);
            }else{
                //si no existe,solo la crea y la persiste
                $infoDependencia = new InfoDependencia();
                $this->importarDatosDependencia($infoDependencia,$dependencia); 
            }
        }catch(Exception $e){
            dd($e->getMessage());
        }
    }
}