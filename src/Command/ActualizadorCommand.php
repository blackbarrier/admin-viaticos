<?php
namespace App\Command;

use App\Entity\InfoReparticionDependencia;
use App\Entity\LogServiceCrons;
use App\Entity\Tasa;
use App\Entity\Tramite;
use App\Repository\DependenciaReparticionRepository;
use App\Repository\DependenciaRepository;
use App\Repository\InfoDependenciaRepository;
use App\Repository\InfoReparticionRepository;
use App\Repository\ReparticionRepository;
use App\Repository\TasaRepository;
use App\Repository\TramiteRepository;
use App\Service\Actualizador;
use App\Service\CreadorHistoricas;
use App\Services\Dpsit;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
class ActualizadorCommand extends Command
{

    private $repo_dependencia;
    private $repo_reparticion;
    private $repo_info_dependencia;
    private $repo_info_reparticion;
    private $servicio_actualizador;
    private $repo_dependencia_reparticion;
   

    protected static $defaultName = 'app:cron_actualizador';
    public function __construct(DependenciaRepository $dependenciaRepository, ReparticionRepository $reparticionRepository, 
    DependenciaReparticionRepository $dependenciaReparticionRepository,InfoReparticionRepository $infoReparticionRepository,
    InfoDependenciaRepository $infoDependenciaRepository,   Actualizador $actualizador){
        parent::__construct();
        $this->repo_dependencia= $dependenciaRepository;
        $this->repo_reparticion = $reparticionRepository;
        $this->servicio_actualizador = $actualizador;    
        $this->repo_dependencia_reparticion = $dependenciaReparticionRepository;   
        $this->repo_info_dependencia = $infoDependenciaRepository;
        $this->repo_info_reparticion = $infoReparticionRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Actualiza las delegaciones disponibles desde nuestra base de datos')
        ;
    }

    protected function execute(InputInterface $input,OutputInterface $output)
    {
       ini_set('memory_limit', '500M');
       $output->writeln("Inicio de ejecución del Cron Actualizador");
       $output->writeln("Procesando, por favor espere.");
       $reparticiones = $this->repo_reparticion->findAll();
       $dpendencias = $this->repo_dependencia->findAll();
       $relaciones = $this->repo_dependencia_reparticion->findAll();
       foreach ($reparticiones as $reparticion) {
            if($this->servicio_actualizador->verificarReparticion($reparticion)){
                $output->writeln("Se modificó o dio de alta una infoReparticion, referente de la Reparticion con el id #".$reparticion->getId());
                $this->servicio_actualizador->actualizarReparticion($reparticion);
            }            
       }
       foreach ($dpendencias as $dependencia) {
            if($this->servicio_actualizador->verificarDependencia($dependencia)){
                $output->writeln("Se modificó  dio de alta una infoDpendencia, referente de la Dependencia con el id #".$dependencia->getId());
                $this->servicio_actualizador->actualizarDependencia($dependencia);
            }            
        }
        foreach ($relaciones as $relacion) {
            
            $infoDependencia = $this->repo_info_dependencia->findOneBy(["dependenciaReferencia" =>$relacion->getDependencia()]);
            $infoReparticion = $this->repo_info_reparticion->findOneBy(["reparticionReferencia" => $relacion->getReparticion()]);           
            if($infoReparticion){
                if(!$infoReparticion->getDependencias()->contains($infoDependencia)){
                    try{
                        $infoReparticion->addDependencia($infoDependencia);
                        $this->repo_info_reparticion->add($infoReparticion,true);
                        $output->writeln("Se añadio la relacion ".$relacion->getReparticion()->getNombre()." => ".$relacion->getDependencia()->getNombre());
                    }catch(Exception $e){
                        echo "error: ".$e->getMessage();
                        die();
                    }
                }
            }
        }
        $output->writeln("Ejecucion de cron finalizada.");
        return 1;
    }
}