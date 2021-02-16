<?php

namespace App\Repository;

use App\Entity\BetroomMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BetroomMatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method BetroomMatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method BetroomMatch[]    findAll()
 * @method BetroomMatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetroomMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BetroomMatch::class);
    }

    // /**
    //  * @return BetroomMatch[] Returns an array of BetroomMatch objects
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
    public function findOneBySomeField($value): ?BetroomMatch
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
