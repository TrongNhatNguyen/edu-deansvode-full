<?php

namespace App\Repository;

use App\Entity\Dean;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dean|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dean|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dean[]    findAll()
 * @method Dean[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeanRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Dean::class);

        $this->entityManager = $entityManager;
    }

    // /**
    //  * @return Dean[] Returns an array of Dean objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dean
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function changePassword($getUser, $password)
    {
            $deanUser = $getUser;
            $deanUser->setPassword1($password);

            $this->entityManager->persist($deanUser);
            $this->entityManager->flush();
    }
}
