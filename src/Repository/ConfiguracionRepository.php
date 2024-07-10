<?php

namespace App\Repository;

use Exception;
use App\Entity\Configuracion;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Configuracion>
 *
 * @method Configuracion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Configuracion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Configuracion[]    findAll()
 * @method Configuracion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Configuracion::class);
    }

    public function agregar(Configuracion $configuracion){
        $em= $this->getEntityManager();
        $em->persist($configuracion);
        $em->flush();
    }
    public function guardar(Configuracion $configuracion)
    {

        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {

            $em->persist($configuracion);
            $em->flush();
            $em->getConnection()->commit();
            return true;
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            return false;
        }
    }
//    /**
//     * @return Configuracion[] Returns an array of Configuracion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Configuracion
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
