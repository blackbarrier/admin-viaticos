<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Usuario>
 *
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    /**
     * Undocumented function
     *
     * @param Usuario $usuario
     * @param boolean $flush
     * @return void
     */
    public function agregar(Usuario $usuario, $flush){
        $this->_em->persist($usuario);
        if($flush){
            $this->_em->flush();
        }

    }

    /**
     * Dado un ID de usuario, busca el password agendado y lo devuelve
     *
     * @param int $id
     * @return mixed
     */
    public function getPassword($id){
        $user= $this->findOneBy(["id" => $id]);
        $pass = $user->getPassword();
        return $pass;
    }

    public function userExists($email,$borrado){
        $user = $this->findOneBy(["email" => $email, "borrado" => $borrado]);
        if($user && $user != "" && !is_array($user)){
            return true;
        }else{
            return false;
        }
    }

//    /**
//     * @return Usuario[] Returns an array of Usuario objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Usuario
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
