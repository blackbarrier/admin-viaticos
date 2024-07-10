<?php

namespace App\Repository;

use App\Entity\InfoReparticion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfoReparticion>
 *
 * @method InfoReparticion|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoReparticion|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoReparticion[]    findAll()
 * @method InfoReparticion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoReparticionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoReparticion::class);
    }

    public function agregar(InfoReparticion $infoReparticion,bool $flush){
        $em= $this->getEntityManager();
        $em->persist($infoReparticion);
        if($flush){
            $em->flush();
        }
    }
    public function remover(InfoReparticion $infoReparticion){
        $em= $this->getEntityManager();
        $em->persist($infoReparticion);       
        $em->flush();    
    }

//    /**
//     * @return InfoReparticion[] Returns an array of InfoReparticion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InfoReparticion
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
