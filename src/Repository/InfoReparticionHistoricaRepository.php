<?php

namespace App\Repository;

use App\Entity\InfoReparticionHistorica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfoReparticionHistorica>
 *
 * @method InfoReparticion|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoReparticion|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoReparticion[]    findAll()
 * @method InfoReparticion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoReparticionHistoricaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoReparticionHistorica::class);
    }

    public function add(InfoReparticionHistorica $infoReparticionHistorica, bool $flush){
        $em=$this->getEntityManager();
        $em->persist($infoReparticionHistorica);
        if($flush){
            $em->flush();
        }
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
