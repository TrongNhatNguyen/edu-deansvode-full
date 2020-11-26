<?php

namespace App\Repository;

use App\Entity\Palm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Palm|null find($id, $lockMode = null, $lockVersion = null)
 * @method Palm|null findOneBy(array $criteria, array $orderBy = null)
 * @method Palm[]    findAll()
 * @method Palm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PalmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Palm::class);
    }

    // /**
    //  * @return Palm[] Returns an array of Palm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Palm
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
