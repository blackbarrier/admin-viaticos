<?php

namespace App\Repository;

use App\Entity\DependenciaReparticion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DependenciaReparticion>
 *
 * @method DependenciaReparticion|null find($id, $lockMode = null, $lockVersion = null)
 * @method DependenciaReparticion|null findOneBy(array $criteria, array $orderBy = null)
 * @method DependenciaReparticion[]    findAll()
 * @method DependenciaReparticion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DependenciaReparticionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DependenciaReparticion::class);
    }

    public function obtenerporReparticion($reparticion){
        $results=$this->findBy(["reparticion" => $reparticion]);
        $arregloDependencias=[];
        if($results){
            foreach ($results as $result) {
               $current= $result->getDependencia();
               $arregloDependencias[] = $current;
            }
        }
        return $arregloDependencias;
    }
//    /**
//     * @return DependenciaReparticion[] Returns an array of DependenciaReparticion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DependenciaReparticion
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
