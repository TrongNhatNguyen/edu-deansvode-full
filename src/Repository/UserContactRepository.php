<?php

namespace App\Repository;

use App\Entity\UserContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserContact[]    findAll()
 * @method UserContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserContactRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, UserContact::class);

        $this->entityManager = $entityManager;
    }

    // /**
    //  * @return UserContact[] Returns an array of UserContact objects
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
    public function findOneBySomeField($value): ?UserContact
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
