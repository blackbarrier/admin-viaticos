<?php

namespace App\Repository;

use Exception;
use App\Entity\Agente;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Agente>
 *
 * @method Agente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agente[]    findAll()
 * @method Agente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agente::class);
    }
   
   
    public function agregar(Agente $agente, bool $flush = false) {
        try {
            $this->getEntityManager()->persist($agente);
            $this->getEntityManager()->flush();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function obtenerPorNumeroDocumentoYCuil($numeroDocumento, $cuil): array
    {    
       return $this->createQueryBuilder('a')
             ->Where('a.numeroDocumento = :numeroDocumento')
             ->OrWhere('a.cuil = :cuil')
             ->setParameter('numeroDocumento', $numeroDocumento)
             ->setParameter('cuil', $cuil)
             ->getQuery()
             ->getResult()
         ;
     }

     /**
      * Devuelve los agentes que matchean el searchTerm que estan dentro de la dependencia especificada
      *
      * @param string $searchTerm
      * @param int $user_dependencia
      * @return mixed
      */
    public function buscarAgentesReparticion($searchTerm, $user_reparticion)
    {
        // Validar los parametros
        if (empty($searchTerm)) {
            throw new \InvalidArgumentException('Search term cannot be empty.');
        }

        if (!is_int($user_reparticion)) {
            throw new \InvalidArgumentException('User dependencia must be an integer.');
        }

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a FROM App\Entity\Agente a
            WHERE (a.legajo LIKE :searchTerm OR a.nombre LIKE :searchTerm OR a.apellido LIKE :searchTerm)
            AND a.reparticion = :user_reparticion AND a.activo = :activo'
        )
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->setParameter('user_reparticion', $user_reparticion)
            ->setParameter('activo',1);
        try {
            $agentes = $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            // Handle cases where the query returns no results
            $agentes = [];
        } catch (\Doctrine\ORM\ORMException $e) {
            // Handle cases where the query fails or returns an error
            throw new \RuntimeException('No se pudo traer agentes de la base de datos.', 0, $e);
        }

        // Return the agents as an array
        // dd($agentes);
        return $agentes;
    }

    /**
     * Devuelve los agentes que matchean el searchTerm
     *
     * @param string $searchTerm
     * @return mixed
     */
    public function buscarAgentes($searchTerm)
    {
        // Validar los parametros
        if (empty($searchTerm)) {
            throw new \InvalidArgumentException('Search term cannot be empty.');
        }

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a FROM App\Entity\Agente a
            WHERE (a.legajo LIKE :searchTerm OR a.nombre LIKE :searchTerm OR a.apellido LIKE :searchTerm) AND a.borrado = :borrado AND a.activo = :activo ')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->setParameter('borrado',0)
            ->setParameter('activo',1);
        try {
            $agentes = $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            // Handle cases where the query returns no results
            $agentes = [];
        } catch (\Doctrine\ORM\ORMException $e) {
            // Handle cases where the query fails or returns an error
            throw new \RuntimeException('No se pudo traer agentes de la base de datos.', 0, $e);
        }

        // Return the agents as an array
        return $agentes;
    }
  
   /*  public function findByNumeroDocumento($numeroDocumento): array
     {
     
        return $this->createQueryBuilder('a')
              ->Where('a.numeroDocumento = :numeroDocumento')
              ->setParameter('numeroDocumento', $numeroDocumento)
              ->getQuery()
              ->getResult()
          ;
      }*/
//    /**
//     * @return Agente[] Returns an array of Agente objects
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

//    public function findOneBySomeField($value): ?Agente
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
