<?php

namespace App\Repository;

use App\Entity\UserRegisterToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserRegisterToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRegisterToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRegisterToken[]    findAll()
 * @method UserRegisterToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRegisterTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRegisterToken::class);
    }

    public function save(UserRegisterToken $userRegisterToken, bool $flush = false): void
    {
        $this->getEntityManager()->persist($userRegisterToken);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserRegisterToken $userRegisterToken, bool $flush = false): void
    {
        $this->getEntityManager()->remove($userRegisterToken);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    // /**
    //  * @return UserRegisterToken[] Returns an array of UserRegisterToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserRegisterToken
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
