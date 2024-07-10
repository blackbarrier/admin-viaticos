<?php

namespace App\Repository;

use App\Entity\InfoDependenciaHistorica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\DependenciaReparticion;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<infoDependenciaHistorica>
 *
 * @method Dependencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dependencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dependencia[]    findAll()
 * @method Dependencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoDependenciaHistoricaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoDependenciaHistorica::class);
    }

    public function add(InfoDependenciaHistorica $infoDependenciaHistorica, bool $flush){
        $em=$this->getEntityManager();
        $em->persist($infoDependenciaHistorica);
        if($flush){
            $em->flush();
        }
    }

   
//    /**
//     * @return Dependencia[] Returns an array of Dependencia objects
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

//    public function findOneBySomeField($value): ?Dependencia
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
