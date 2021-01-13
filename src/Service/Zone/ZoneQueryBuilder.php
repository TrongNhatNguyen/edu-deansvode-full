<?php

namespace App\Service\Zone;

use App\DTO\QueryObject\Zone\ZoneListQuery;
use App\Repository\ZoneRepository;

class ZoneQueryBuilder
{
    private $zoneRepository;

    public function __construct(ZoneRepository $zoneRepository)
    {
        $this->zoneRepository = $zoneRepository;
    }


    public function buildZoneListQuery(array $params)
    {
        $zoneListQuery = new ZoneListQuery();

        foreach ($params as $key => $value) {
            // paginate:
            if ($key === "page") {
                $zoneListQuery->page = $value;
            }
            if ($key === "limit") {
                $zoneListQuery->limit = $value;
            }

            // sort:
            if ($key === "name") {
                $zoneListQuery->orders[$key] = $value;
            }

            // filter:
            if ($key === "status") {
                $zoneListQuery->conditions[$key] = $value;
            }

            // search:
            if ($key === "fieldSearch") {
                $zoneListQuery->conditions['search'][$key] = $value;
            }
            if ($key === "textSearch") {
                $zoneListQuery->conditions['search'][$key] = $value;
            }
        }

        return $zoneListQuery;
    }

    public function getZoneByListQuery($listQuery)
    {
        $queryBuilder = $this->zoneRepository->createQueryBuilder('z')->select();

        if (!empty($listQuery->conditions)) {
            foreach ($listQuery->conditions as $key => $value) {
                if ($value == null) {
                    $key = "";
                }
                switch ($key) {
                    case 'status':
                        $queryBuilder->andWhere('z.status = :val')
                        ->setParameter('val', $value);
                        break;
                        
                    case 'search':
                        $queryBuilder->andWhere('z.'.$value['fieldSearch'].' LIKE :keyword')
                        ->setParameter('keyword', '%'.$value['textSearch'].'%');
                        break;
                    default:
                        break;
                }
            }
        }

        if (!empty($listQuery->orders)) {
            foreach ($listQuery->orders as $key => $value) {
                $queryBuilder->addOrderBy('z.'.$key, $value);
            }
        }

        return $queryBuilder;
    }

    public function getAllZonesQuery()
    {
        return $this->zoneRepository->createQueryBuilder('z')->select();
    }
}
