<?php

namespace App\Repository;

use App\Entity\Matcha;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Matcha|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matcha|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matcha[]    findAll()
 * @method Matcha[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matcha::class);
    }

    // /**
    //  * @return Matcha[] Returns an array of Matcha objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Matcha
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
