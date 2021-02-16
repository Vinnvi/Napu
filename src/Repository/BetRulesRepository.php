<?php

namespace App\Repository;

use App\Entity\BetRules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BetRules|null find($id, $lockMode = null, $lockVersion = null)
 * @method BetRules|null findOneBy(array $criteria, array $orderBy = null)
 * @method BetRules[]    findAll()
 * @method BetRules[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetRulesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BetRules::class);
    }

    // /**
    //  * @return BetRules[] Returns an array of BetRules objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BetRules
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
