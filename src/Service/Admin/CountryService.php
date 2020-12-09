<?php

namespace App\Service\Admin;

use App\DTO\QueryObject\Country\CountryListQuery;
use App\Repository\CountryRepository;
use App\Repository\ZoneRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    public function createCountryAction($data)
    {
        try {
            // validate:
            $validCountry = $this->validCountryCustom($data);
            if (!empty($validCountry)) {
                return [
                    'status' => 'failed',
                    'error' => $validCountry
                ];
            }
            // data default:
            $data['country_sort'] = 0;

            if (isset($data['country_status']) && $data['country_status'] === "on") {
                $data['country_status'] = 0;
            } else {
                $data['country_status'] = 1;
            }

            // relationship:
            $zone = $this->zoneRepository->find((int) $data['zone_id']);

            $countryData = [
                'zone' => $zone,
                'name' => $data['country_name'],
                'slug' => $data['country_slug'],
                'iso_code' => $data['country_iso_code'],
                'sort' => $data['country_sort'],
                'status' => $data['country_status']
            ];

            $this->countryRepository->createNewCountry($countryData);

            return [
                'status' => 'success',
                'message' => 'create new country is successfully!',
                'url' => $this->router->generate('admin_country')
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function updateCountryAction($data)
    {
        try {
            // data default:
            $data['country_sort'] = 0;

            if (isset($data['country_status']) && $data['country_status'] === "on") {
                $data['country_status'] = 1;
            } else {
                $data['country_status'] = 0;
            }

            // relationship:
            $zone = $this->zoneRepository->find((int) $data['zone_id']);

            $countryData = [
                'zone' => $zone,
                'id' => $data['country_id'],
                'name' => $data['country_name'],
                'slug' => $data['country_slug'],
                'iso_code' => $data['country_iso_code'],
                'sort' => $data['country_sort'],
                'status' => $data['country_status']
            ];

            $this->countryRepository->updateCountry($countryData);

            return [
                'status' => 'success',
                'message' => 'update country is successfully!',
                'url' => $this->router->generate('admin_country')
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function updateStatusAction($data)
    {
        $getCountry = $this->getCountryById($data['country_id']);

        $data['zone_id'] = $getCountry->getZone()->getId();
        $data['country_name'] = $getCountry->getName();
        $data['country_slug'] = $getCountry->getSlug();
        $data['country_iso_code'] = $getCountry->getIsoCode();

        return $this->updateCountryAction($data);
    }

    public function deleteCountryAction($id)
    {
        try {
            if (!$this->getCountryById($id)) {
                return [
                    'status' => 'failed',
                    'error' => 'cannot delete country that do not exist'
                ];
            }

            $this->countryRepository->deleteCountry($id);

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

    public function validCountryCustom($data)
    {
        $error = [];
        if ($this->getCountryByName($data['country_name'])) {
            $error['name'] = "Name is already exists.";
        }
        if ($this->getCountryBySlug($data['country_slug'])) {
            $error['slug'] = "Alias is already exists.";
        }
        if ($this->getCountryByIsoCode($data['country_iso_code'])) {
            $error['isoCode'] = "iso-code is already exists.";
        }
        if (!$this->zoneRepository->findOneBy(['id' => $data['zone-id']])) {
            $error['zone'] = "this area is not exists.";
        }
        
        return $error;
    }
    // =====================================

    // save value params (search-sort-filter by query):
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

    public function getAllCountriesQuery()
    {
        $queryBuilder = $this->countryRepository->createQueryBuilder('c');

        return $queryBuilder->select();
    }

    public function getListCountry(CountryListQuery $countryListQuery)
    {
        $queryBuilder = $this->countryRepository->createQueryBuilder('c')->select();

        if (!empty($countryListQuery->conditions)) {
            foreach ($countryListQuery->conditions as $key => $value) {
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

        if (!empty($countryListQuery->orders)) {
            foreach ($countryListQuery->orders as $key => $value) {
                $queryBuilder->addOrderBy('c.'.$key, $value);
            }
        }
        
        if (!empty($countryListQuery->page)) {
            $page = $countryListQuery->page;
        } else {
            $page = 1;
        }

        if (!empty($countryListQuery->limit)) {
            $limit = $countryListQuery->limit;
        } else {
            $limit = 25;
        }

        return [
            'page' => $page,
            'limit' => $limit,
            'queryBuilder' => $queryBuilder
        ];
    }


    //= count items:
    public function countAllItems()
    {
        $Results = count((array) $this->getAllCountries());

        return $Results;
    }
    public function countPagesByItems($pageSize = 25)
    {
        $totalResults = count((array) $this->getAllCountries());

        $results = ceil($totalResults / $pageSize);

        return $results;
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
        $criteria = ['name' => $name, 'status' => 1];
        $result = $this->countryRepository->findOneBy((array) $criteria);

        return $result;
    }

    public function getCountryBySlug($slug)
    {
        $criteria = ['slug' => $slug, 'status' => 1];
        $result = $this->countryRepository->findOneBy((array) $criteria);

        return $result;
    }
    
    public function getCountryByIsoCode($isoCode)
    {
        $criteria = ['isoCode' => $isoCode, 'status' => 1];
        $result = $this->countryRepository->findOneBy((array) $criteria);

        return $result;
    }
}
