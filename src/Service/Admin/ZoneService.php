<?php

namespace App\Service\Admin;

use App\DTO\QueryObject\Zone\ZoneListQuery;
use App\Entity\Zone;
use App\Repository\ZoneRepository;
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
    public function createZone($createRequest)
    {
        try {
            $zone = new Zone();

            $zone->setName($createRequest->name);
            $zone->setSlug($createRequest->slug);
            $zone->setImage($createRequest->image);
            $zone->setSort($createRequest->sort);
            $zone->setStatus($createRequest->status);
            $zone->setCreatedAt(new \DateTime('now'));
            $zone->setUpdatedAt(new \DateTime('now'));
            
            $this->zoneRepository->fetching($zone);

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

    public function updateZone($updateRequest)
    {
        try {
            $zone = $this->zoneRepository->find((int) $updateRequest->id);

            $zone->setName($updateRequest->name);
            $zone->setSlug($updateRequest->slug);
            $zone->setImage($updateRequest->image);
            $zone->setSort($updateRequest->sort);
            $zone->setStatus($updateRequest->status);
            $zone->setUpdatedAt(new \DateTime('now'));

            $this->zoneRepository->fetching($zone);

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

    public function updateStatus($updateStatusRequest)
    {
        try {
            $zone = $this->zoneRepository->find((int) $updateStatusRequest->id);

            $zone->setStatus($updateStatusRequest->status);
            $zone->setUpdatedAt(new \DateTime('now'));

            $this->zoneRepository->fetching($zone);

            return [
                'status' => 'success',
                'message' => 'update area successfully!',
                'url' => $this->router->generate('admin_area')
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function removeZone($id)
    {
        try {
            $zone = $this->zoneRepository->find((int) $id);

            $this->zoneRepository->remove($zone);

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
    /// ================================.

    // [search-sort-filter by query params]:
    public function getAllZonesQuery()
    {
        $queryBuilder = $this->zoneRepository->createQueryBuilder('z');
        return $queryBuilder->select();
    }

    public function getZoneQueryBuilder($listQuery)
    {
        $queryBuilder = $this->getListQueryByConditions($listQuery);

        return $queryBuilder;
    }

    public function getExportZoneList($reqParams)
    {
        $listQuery = $this->buildZoneListQuery($reqParams);

        $queryBuilder = $this->getListQueryByConditions($listQuery);

        return $queryBuilder->getQuery()->getResult();
    }

    public function getListQueryByConditions($ListQuery)
    {
        $queryBuilder = $this->zoneRepository->createQueryBuilder('z')->select();
        if (!empty($ListQuery->conditions)) {
            foreach ($ListQuery->conditions as $key => $value) {
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

        if (!empty($ListQuery->orders)) {
            foreach ($ListQuery->orders as $key => $value) {
                $queryBuilder->addOrderBy('z.'.$key, $value);
            }
        }

        return $queryBuilder;
    }

    public function getListQueryByPagination($ListQuery)
    {
        if (!empty($ListQuery->page)) {
            $page = $ListQuery->page;
        }

        if (!empty($ListQuery->limit)) {
            $limit = $ListQuery->limit;
        }

        return ['page' => $page, 'limit' => $limit];
    }

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
