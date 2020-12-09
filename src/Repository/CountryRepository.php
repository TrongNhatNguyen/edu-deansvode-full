<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Country::class);
        $this->entityManager = $entityManager;
    }

    // /**
    //  * @return Country[] Returns an array of Country objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Country
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // ========== CRUD:
    public function createNewCountry($countryData)
    {
        $country = new Country();

        $country->setName($countryData['name']);
        $country->setZone($countryData['zone']);
        $country->setSlug($countryData['slug']);
        $country->setIsoCode($countryData['iso_code']);
        $country->setSort($countryData['sort']);
        $country->setStatus($countryData['status']);
        $country->setCreatedAt(new \DateTime('now'));
        $country->setUpdatedAt(new \DateTime('now'));

        $this->entityManager->persist($country);
        $this->entityManager->flush();
    }

    public function updateCountry($countryData)
    {
        $countryUpdate = $this->findOneBy(['id' => $countryData['id']]);

        $countryUpdate->setName($countryData['name']);
        $countryUpdate->setZone($countryData['zone']);
        $countryUpdate->setSlug($countryData['slug']);
        $countryUpdate->setIsoCode($countryData['iso_code']);
        $countryUpdate->setSort($countryData['sort']);
        $countryUpdate->setStatus($countryData['status']);
        $countryUpdate->setUpdatedAt(new \DateTime('now'));

        $this->entityManager->persist($countryUpdate);
        $this->entityManager->flush();
    }

    public function deleteCountry($id)
    {
        $countryDel = $this->find($id);

        $this->entityManager->remove($countryDel);
        $this->entityManager->flush();
    }
}
