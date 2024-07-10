<?php

namespace App\Repository;

use App\Entity\Plantilla;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plantilla>
 *
 * @method Plantilla|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plantilla|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plantilla[]    findAll()
 * @method Plantilla[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlantillaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plantilla::class);
    }

    public function agregar(Plantilla $plantilla, $flush = true){
        $em = $this->getEntityManager();
        $em->persist($plantilla);
        if($flush){
            $em->flush();
        }
    }
    
    public function actualizar(Plantilla $plantilla, $flush = true){
        $em = $this->getEntityManager();
        if($flush){
            $em->flush();
        }
    }


    /**
     * Devuelve las plantillas que contienen el id del agente provisto
     *
     * @param int $idAgente
     * @return array|mixed
     */
    public function obtenerPlantillasConAgente($idAgente){
        $result = new ResultSetMapping();
        $result->addScalarResult("plantilla_id", "plantilla");
        $query = $this->getEntityManager()->createNativeQuery(
            "SELECT plantilla_id from plantilla_agente where agente_id = :idAgente ",$result
            )->setParameter("idAgente",$idAgente);
        $plantillasR= $query->getResult();
        $array = [];
        foreach($plantillasR as $plantilla){
            $entidad = $this->findBy(["id" => $plantilla]);
            if($entidad){
                array_push($array,$entidad[0]);
            }
        }
        return $array;
    }
    
    

//    /**
//     * @return Plantilla[] Returns an array of Plantilla objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Plantilla
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * Dada una plantilla genera un json para solicitar viaticos con la mismoa, en blanco
     * 
     * output de ejemplo:
     *  [
     *   { legajo: '204685 [20137968348]', nyap: 'AGUILA, GUSTAVO ROBERTO', dep: 'DIRECCION GENERAL DE ADMINISTRACION', categ: '15', modulo: 8400.00 , ciento_cincuenta: 0, cien: 0, setenta: 0, cincuenta: 0, cuarenta: 0, veinte: 0, movilidad: 0, importe: 0 },
     *   { legajo: '708859 [20160858134]', nyap: 'BUZED, ENRIQUE DANIEL', dep: 'DIRECCION GENERAL DE ADMINISTRACION', categ: '16', modulo: 8400.00 , ciento_cincuenta: 0, cien: 0, setenta: 0, cincuenta: 0, cuarenta: 0, veinte: 0, movilidad: 0, importe: 0 },
     *   { legajo: '204644 [20137968355]', nyap: 'FANTINO, ALEJANDRO', dep: 'DIRECCION GENERAL DE ADMINISTRACION', categ: '15', modulo: 8400.00 , ciento_cincuenta: 0, cien: 0, setenta: 0, cincuenta: 0, cuarenta: 0, veinte: 0, movilidad: 0, importe: 0 }
     * ];
     * 
     * En un futuro probablemente se reemplaze el monto del modulo dinámicamente
     *
     * @param Plantilla $plantilla
     * @return mixed
     */
    public function generarPlantillaEnBlanco_Json(Plantilla $plantilla){
        $arrayAgente = array();
        $agentes = $plantilla->getAgentes();
        foreach ($agentes as $agente) {
            $jsonArray=array(
                'cuil' => $agente->getCuil(),
                'nyap' => $agente->getNombre()." ".$agente->getApellido(),
                'dep' => $agente->getDependencia()->getNombre(),
                'careg' => $agente->getCategoria(),
                'modulo' => 8400.00,
                'ciento_cincuenta' => 0,
                'cien' => 0,
                'setenta' => 0,
                'cincuenta' => 0,
                'cuarenta' =>0,
                'veinte' =>0,
                'movilidad' =>0,
                'importe' =>0,
            );
            array_push($arrayAgente,$jsonArray);
        }
        return $arrayAgente;
      
    }


    

}

