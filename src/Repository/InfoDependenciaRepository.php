<?php

namespace App\Repository;

use App\Entity\InfoDependencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfoDependencia>
 *
 * @method InfoDependencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoDependencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoDependencia[]    findAll()
 * @method InfoDependencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoDependenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoDependencia::class);
    }

    public function agregar(InfoDependencia $infoDependencia,bool $flush){
        $em= $this->getEntityManager();
        $em->persist($infoDependencia);
        if($flush){
            $em->flush();
        }
    }
    public function remover(InfoDependencia $infoDependencia){
        $em= $this->getEntityManager();
        $em->remove($infoDependencia);      
        $em->flush();
    }

}
