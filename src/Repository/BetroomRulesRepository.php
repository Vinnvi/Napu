<?php

namespace App\Repository;

use App\Entity\BetroomRules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BetroomRules|null find($id, $lockMode = null, $lockVersion = null)
 * @method BetroomRules|null findOneBy(array $criteria, array $orderBy = null)
 * @method BetroomRules[]    findAll()
 * @method BetroomRules[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetroomRulesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BetroomRules::class);
    }

    // /**
    //  * @return BetroomRules[] Returns an array of BetroomRules objects
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
    public function findOneBySomeField($value): ?BetroomRules
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
