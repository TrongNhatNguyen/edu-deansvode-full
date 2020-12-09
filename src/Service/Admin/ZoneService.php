<?php

namespace App\Service\Admin;

use App\DTO\QueryObject\Zone\ZoneListQuery;
use App\Repository\ZoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ZoneService
{
    private $zoneRepository;
    private $router;

    public function __construct(ZoneRepository $zoneRepository, RouterInterface $router)
    {
        $this->zoneRepository = $zoneRepository;
        $this->router = $router;
    }

    /// ================================ CRUD:
    public function createZoneAction($data)
    {
        try {
            // validate:
            $validArea = $this->validZoneCustom($data);
            if (!empty($validArea)) {
                return [
                    'status' => 'failed',
                    'error' => $validArea
                ];
            }

            // data default:
            $data['area_image'] = "no-image.png";
            $data['area_sort'] = 0;

            if (isset($data['area_status']) && $data['area_status'] === "on") {
                $data['area_status'] = 1;
            } else {
                $data['area_status'] = 0;
            }

            $zoneData = [
                'name' => $data['area_name'],
                'slug' => $data['area_slug'],
                'image' => $data['area_image'],
                'sort' => $data['area_sort'],
                'status' => $data['area_status']
            ];

            $this->zoneRepository->createNewZone($zoneData);

            return [
                'status' => 'success',
                'message' => 'create new area is successfully!',
                'url' => $this->router->generate('admin_area')
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function updateZoneAction($data)
    {
        try {
            // data default:
            $data['area_image'] = "no-image.png";
            $data['area_sort'] = 0;

            if (isset($data['area_status']) && $data['area_status'] === "on") {
                $data['area_status'] = 1;
            } else {
                $data['area_status'] = 0;
            }

            $zoneData = [
                'id' => $data['area_id'],
                'name' => $data['area_name'],
                'slug' => $data['area_slug'],
                'image' => $data['area_image'],
                'sort' => $data['area_sort'],
                'status' => $data['area_status']
            ];

            $this->zoneRepository->updateZone($zoneData);

            return [
                'status' => 'success',
                'message' => 'update area is successfully!',
                'url' => $this->router->generate('admin_area')
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
        $getArea = $this->getZoneById($data['area_id']);

        $data['area_name'] = $getArea->getName();
        $data['area_slug'] = $getArea->getSlug();

        return $this->updateZoneAction($data);
    }

    public function deleteZoneAction($id)
    {
        try {
            if (!$this->getZoneById($id)) {
                return [
                    'status' => 'failed',
                    'error' => 'cannot delete area that do not exist'
                ];
            }
            $this->zoneRepository->deleteZone($id);

            return [
                'status' => 'success',
                'message' => 'successfully deleted this area!',
                'url' => $this->router->generate('admin_area')
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function validZoneCustom($data)
    {
        $error = [];
        if ($this->getZoneByName($data['area_name'])) {
            $error['name'] = "Name is already exists.";
        }
        if ($this->getZoneBySlug($data['area_slug'])) {
            $error['slug'] = "Alias is already exists.";
        }
        
        return $error;
    }
    /// ================================.

    //save value params (search-sort-filter by query):
    public function buildZoneListQuery($params)
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

    public function getAllZonesQuery()
    {
        $queryBuilder = $this->zoneRepository->createQueryBuilder('z');
        return $queryBuilder->select();
    }

    public function getListZoneQuery($zoneListQuery)
    {
        $queryBuilder = $this->zoneRepository->createQueryBuilder('z')->select();

        if (!empty($zoneListQuery->conditions)) {
            foreach ($zoneListQuery->conditions as $key => $value) {
                if ($value == null) {
                    $key = "";
                }
                switch ($key) {
                    case 'status':
                        $queryBuilder->andWhere('z.'.$key.' = :val')
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

        if (!empty($zoneListQuery->orders)) {
            foreach ($zoneListQuery->orders as $key => $value) {
                $queryBuilder->addOrderBy('z.'.$key, $value);
            }
        }

        if (!empty($zoneListQuery->page)) {
            $page = $zoneListQuery->page;
        } else {
            $page = 1;
        }

        if (!empty($zoneListQuery->limit)) {
            $limit = $zoneListQuery->limit;
        } else {
            $limit = 25;
        }

        return [
            'page' => $page,
            'limit' => $limit,
            'queryBuilder' => $queryBuilder
        ];
    }



    // == count items:
    public function countAllItems()
    {
        $Results = count((array) $this->getAllZones());

        return $Results;
    }
    public function countPagesByItems($pageSize = 25)
    {
        $totalResults = count((array) $this->getAllZones());

        if ($totalResults % $pageSize == 0) {
            $results = floor($totalResults / $pageSize);
        } else {
            $results = floor($totalResults / $pageSize + 1);
        }

        return $results;
    }


    // =============================== default:
    public function getAllZones()
    {
        return $this->zoneRepository->findAll();
    }

    public function getZoneById($id)
    {
        $result = $this->zoneRepository->find($id);

        return $result;
    }

    public function getZoneByName($name)
    {
        $criteria = ['name' => $name];
        $result = $this->zoneRepository->findOneBy((array) $criteria);

        return $result;
    }

    public function getZoneBySlug($slug)
    {
        $criteria = ['slug' => $slug];
        $result = $this->zoneRepository->findOneBy((array) $criteria);

        return $result;
    }
}
