<?php

namespace App\Repository;

use App\Entity\VoteSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VoteSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoteSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoteSession[]    findAll()
 * @method VoteSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteSessionRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, VoteSession::class);
        $this->entityManager = $entityManager;
    }

    // /**
    //  * @return VoteSession[] Returns an array of VoteSession objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VoteSession
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function fetching($voteSession)
    {
        $this->entityManager->persist($voteSession);
        $this->entityManager->flush();
    }

    public function remove($voteSession)
    {
        $this->entityManager->remove($voteSession);
        $this->entityManager->flush();
    }
}
