<?php

namespace App\Repository;

use App\Entity\Reparticion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reparticion>
 *
 * @method Reparticion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reparticion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reparticion[]    findAll()
 * @method Reparticion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparticionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reparticion::class);
    }

//    /**
//     * @return Reparticion[] Returns an array of Reparticion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reparticion
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
