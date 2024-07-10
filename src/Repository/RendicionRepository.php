<?php

namespace App\Repository;

use App\Entity\Rendicion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rendicion>
 *
 * @method Rendicion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rendicion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rendicion[]    findAll()
 * @method Rendicion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendicionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rendicion::class);
    }

    public function agregar(Rendicion $rendicion, $flush){
        $this->_em->persist($rendicion);
        if($flush){
            $this->_em->flush();
        }

    }
//    /**
//     * @return Rendicion[] Returns an array of Rendicion objects
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

//    public function findOneBySomeField($value): ?Rendicion
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
