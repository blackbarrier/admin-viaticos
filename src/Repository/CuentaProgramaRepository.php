<?php

namespace App\Repository;

use App\Entity\CuentaPrograma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Actividad>
 *
 * @method CuentaPrograma|null find($id, $lockMode = null, $lockVersion = null)
 * @method CuentaPrograma|null findOneBy(array $criteria, array $orderBy = null)
 * @method CuentaPrograma[]    findAll()
 * @method CuentaPrograma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuentaProgramaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CuentaPrograma::class);
    }

    public function add(CuentaPrograma $cuenta, bool $flush){
        $em= $this->getEntityManager();
        $em->persist($cuenta);
        if($flush){
            $em->flush();
        }
    }
//    /**
//     * @return CuentaPrograma[] Returns an array of Actividad objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Actividad
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
