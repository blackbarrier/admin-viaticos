<?php

namespace App\Repository;

use App\Entity\Actividad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Actividad>
 *
 * @method Actividad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actividad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actividad[]    findAll()
 * @method Actividad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActividadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actividad::class);
    }

    public function add(Actividad $actividad, bool $flush){
        $em= $this->getEntityManager();
        $em->persist($actividad);
        if($flush){
            $em->flush();
        }
    }
//    /**
//     * @return Actividad[] Returns an array of Actividad objects
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
