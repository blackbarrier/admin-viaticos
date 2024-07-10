<?php

namespace App\Repository;

use App\Entity\Feriado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Feriado>
 *
 * @method Feriado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feriado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feriado[]    findAll()
 * @method Feriado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeriadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feriado::class);
    }
    /*
    * persiste un feriado
    *
    * @param Usuario $feriado
    * @param boolean $flush
    * @return void
    */
   public function agregar(Feriado $feriado, $flush){
       $this->_em->persist($feriado);
       if($flush){
           $this->_em->flush();
       }
   }
   
   
   public function remover(Feriado $feriado){
       $em=$this->getEntityManager();
       $em->remove($feriado);
       $em->flush();
   }

//    /**
//     * @return Feriado[] Returns an array of Feriado objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Feriado
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
