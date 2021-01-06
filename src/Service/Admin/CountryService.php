<?php

namespace App\Service\Admin;

use App\DTO\QueryObject\Country\CountryListQuery;
use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Repository\ZoneRepository;
use Symfony\Component\Routing\RouterInterface;

class CountryService
{
    private $countryRepository;
    private $zoneRepository;
    private $router;

    public function __construct(
        CountryRepository $countryRepository,
        ZoneRepository $zoneRepository,
        RouterInterface $router
    ) {
        $this->countryRepository = $countryRepository;
        $this->zoneRepository = $zoneRepository;
        $this->router = $router;
    }

    // =============== CRUD:
    public function createCountry($createRequest)
    {
        try {
            $zone = $this->zoneRepository->find((int) $createRequest->zoneId);
            $country = new Country();

            $country->setZone($zone);
            $country->setName($createRequest->name);
            $country->setSlug($createRequest->slug);
            $country->setIsoCode($createRequest->isoCode);
            $country->setSort($createRequest->sort);
            $country->setStatus($createRequest->status);
            $country->setCreatedAt(new \DateTime('now'));
            $country->setUpdatedAt(new \DateTime('now'));
            
            $this->countryRepository->fetching($country);

            return [
                'status' => 'success',
                'message' => 'create new country successfully!',
                'url' => $this->router->generate('admin_country')
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function updateCountry($updateRequest)
    {
        try {
            $zone = $this->zoneRepository->find((int) $updateRequest->zoneId);
            $country = $this->countryRepository->find((int) $updateRequest->id);

            $country->setZone($zone);
            $country->setName($updateRequest->name);
            $country->setSlug($updateRequest->slug);
            $country->setIsoCode($updateRequest->isoCode);
            $country->setSort($updateRequest->sort);
            $country->setStatus($updateRequest->status);
            $country->setUpdatedAt(new \DateTime('now'));

            $this->countryRepository->fetching($country);

            return [
                'status' => 'success',
                'message' => 'update country successfully!',
                'url' => $this->router->generate('admin_country')
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function updateStatusCountry($updateStatusRequest)
    {
        try {
            $country = $this->countryRepository->find((int) $updateStatusRequest->id);

            $country->setStatus($updateStatusRequest->status);
            $country->setUpdatedAt(new \DateTime('now'));

            $this->countryRepository->fetching($country);

            return [
                'status' => 'success',
                'message' => 'Update status successfully!'
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function deleteCountry($id)
    {
        try {
            $country = $this->countryRepository->find((int) $id);

            $this->countryRepository->remove($country);

            return [
                'status' => 'success',
                'message' => 'successfully deleted this country!',
                'url' => $this->router->generate('admin_country')
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }
    // =====================================

    // (search-sort-filter by query):
    public function getAllCountriesQuery()
    {
        $queryBuilder = $this->countryRepository->createQueryBuilder('c');

        return $queryBuilder->select();
    }
    
    public function getExportCountryList($reqParams)
    {
        $listQuery = $this->buildCountryListQuery($reqParams);
        $queryBuilder = $this->getCountryQueryBuilder($listQuery);

        return $queryBuilder->getQuery()->getResult();
    }

    public function getCountryQueryBuilder($listQuery)
    {
        $queryBuilder = $this->countryRepository->createQueryBuilder('c')->select();
        if (!empty($listQuery->conditions)) {
            foreach ($listQuery->conditions as $key => $value) {
                if ($value == null) {
                    $key = "";
                }

                switch ($key) {
                    case 'zone_id':
                        $zone = $this->zoneRepository->find($value);
                        $queryBuilder->andWhere('c.zone = :zone_val')
                        ->setParameter('zone_val', $zone);
                        break;

                    case 'status':
                        $queryBuilder->andWhere('c.'.$key.' = :val')
                        ->setParameter('val', $value);
                        break;

                    case 'search':
                        $queryBuilder->andWhere('c.'.$value['fieldSearch'].' LIKE :keyword')
                        ->setParameter('keyword', '%'.$value['textSearch'].'%');
                        break;
                    default:
                        break;
                }
            }
        }

        if (!empty($listQuery->orders)) {
            foreach ($listQuery->orders as $key => $value) {
                $queryBuilder->addOrderBy('c.'.$key, $value);
            }
        }

        return $queryBuilder;
    }

    public function buildCountryListQuery(array $params)
    {
        $countryListQuery = new CountryListQuery();

        foreach ($params as $key => $value) {
            // pagination:
            if ($key === 'limit') {
                $countryListQuery->limit = $value;
            }
            if ($key === 'page') {
                $countryListQuery->page = $value;

                if (empty($countryListQuery->limit)) {
                    $countryListQuery->offset = 0;
                } else {
                    $countryListQuery->offset = $countryListQuery->limit * (min($value, 1) - 1);
                }
            }

            // sort:
            if ($key === "name") {
                $countryListQuery->orders[$key] = $value;
            }

            // filter:
            if ($key === "zone_id") {
                $countryListQuery->conditions[$key] = $value;
            }
            if ($key === "status") {
                $countryListQuery->conditions[$key] = $value;
            }

            //search:
            if ($key === "fieldSearch") {
                $countryListQuery->conditions['search'][$key] = $value;
            }
            if ($key === 'textSearch') {
                $countryListQuery->conditions['search'][$key] = $value;
            }
        }

        return $countryListQuery;
    }


    // =========== function default:
    public function getAllCountries()
    {
        $result = $this->countryRepository->findAll();

        return $result;
    }

    public function getCountryById($id)
    {
        $result = $this->countryRepository->find((int) $id);

        return $result;
    }

    public function getCountryByName($name)
    {
        $criteria = ['name' => $name];
        $result = $this->countryRepository->findOneBy((array) $criteria);

        return $result;
    }

    public function getCountryBySlug($slug)
    {
        $criteria = ['slug' => $slug];
        $result = $this->countryRepository->findOneBy((array) $criteria);

        return $result;
    }
    
    public function getCountryByIsoCode($isoCode)
    {
        $criteria = ['isoCode' => $isoCode];
        $result = $this->countryRepository->findOneBy((array) $criteria);

        return $result;
    }
}
