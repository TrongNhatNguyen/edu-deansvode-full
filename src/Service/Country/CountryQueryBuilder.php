<?php

namespace App\Service\Country;

use App\DTO\QueryObject\Country\CountryListQuery;
use App\Repository\CountryRepository;
use App\Service\Zone\ZoneFetcher;

class CountryQueryBuilder
{
    private $countryRepository;
    private $zoneFetcher;

    public function __construct(CountryRepository $countryRepository, ZoneFetcher $zoneFetcher)
    {
        $this->countryRepository = $countryRepository;
        $this->zoneFetcher = $zoneFetcher;
    }


    public function buildCountryListQuery(array $params)
    {
        $countryListQuery = new CountryListQuery();

        foreach ($params as $key => $value) {
            // paginate:
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

    public function getCountryByListQuery($listQuery)
    {
        $queryBuilder = $this->countryRepository->createQueryBuilder('c')->select();
        if (!empty($listQuery->conditions)) {
            foreach ($listQuery->conditions as $key => $value) {
                if ($value == null) {
                    $key = "";
                }

                switch ($key) {
                    case 'zone_id':
                        $zone = $this->zoneFetcher->getZoneById($value);
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

    public function getAllCountriesQuery()
    {
        return $queryBuilder = $this->countryRepository->createQueryBuilder('c')->select();
    }
}
