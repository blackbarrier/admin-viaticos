<?php

namespace App\Repository;

use App\Entity\Estado;
use App\Entity\Viatico;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @extends ServiceEntityRepository<Viatico>
 *
 * @method Viatico|null find($id, $lockMode = null, $lockVersion = null)
 * @method Viatico|null findOneBy(array $criteria, array $orderBy = null)
 * @method Viatico[]    findAll()
 * @method Viatico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViaticoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Viatico::class);
    }

    public function agregar(Viatico $viatico, $flush){
        $em= $this->getEntityManager();
        $em->persist($viatico);
        if($flush){
            $em->flush();
        }
    }

    public function obtenerViaticos()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.fechaPedido', 'DESC')
            // ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    public function obtenerViaticosReparticion($reparticion)
    {
        return $this->findby(["reparticion" => $reparticion->getNombre(), "estado" => Estado::SOLICITADO ]);
       /* $query = $this->getEntityManager()
            ->createQuery("SELECT dependencia, count(id) AS cantidad 
                        FROM viatico
                        WHERE estado_id=1 AND reparticion=".$reparticion->getId()."
                        GROUP BY dependencia");

        $dependencias = $query->getResult();
        

        return $dependencias;*/
    }

    /**
     * Hace un conteo de estados en el conjunto de viaticos para una reparticion.
     * @param integer $estado_id
     * @param string $nombreReparricion
     * @return array
     */
    public function conteoViaticosPorEstadoYReparticion($estado_id, $nombreReparticion) {
        try {            
            $sql = "SELECT v.dependencia AS dependencia , COUNT(*) as cantViaticos
                FROM viatico v 
                WHERE v.reparticion ='$nombreReparticion' AND v.estado_id IN ($estado_id) 
                GROUP BY dependencia";
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
            $result = $stmt->executeQuery()->fetchAllAssociative();
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function ObtenerViaticosRendidosMes($agente, $mes, $anio)
    {
        $estados_array = [Estado::RENDIDO];
        $estados = implode(',',$estados_array);
        $query = $this->getEntityManager()
            ->createQuery("SELECT v FROM App:Viatico v  
               WHERE v.agente ='".$agente->getId()."'  
               AND v.mes  = ".$mes."
               AND v.anio  = ".$anio."
               and v.estado IN (".'2'.")");
        return $query->getResult();
    }
//    /**
//     * @return Viatico[] Returns an array of Viatico objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Viatico
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
