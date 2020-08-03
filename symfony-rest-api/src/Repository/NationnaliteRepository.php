<?php

namespace App\Repository;

use App\Entity\Nationnalite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nationnalite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nationnalite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nationnalite[]    findAll()
 * @method Nationnalite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NationnaliteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nationnalite::class);
    }

    // /**
    //  * @return Nationnalite[] Returns an array of Nationnalite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Nationnalite
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
