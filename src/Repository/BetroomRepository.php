<?php

namespace App\Repository;

use App\Entity\Betroom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Betroom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Betroom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Betroom[]    findAll()
 * @method Betroom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetroomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Betroom::class);
    }

    // /**
    //  * @return Betroom[] Returns an array of Betroom objects
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
    public function findOneBySomeField($value): ?Betroom
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
