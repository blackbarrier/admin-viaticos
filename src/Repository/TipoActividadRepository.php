<?php

namespace App\Repository;

use App\Entity\TipoActividad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoActividad>
 *
 * @method TipoActividad|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoActividad|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoActividad[]    findAll()
 * @method TipoActividad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoActividadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoActividad::class);
    }

//    /**
//     * @return TipoActividad[] Returns an array of TipoActividad objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TipoActividad
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
