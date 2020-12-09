<?php

namespace App\Service\Web;

use App\Repository\ZoneRepository;

class ZoneService
{
    private $zoneRepository;

    public function __construct(ZoneRepository $zoneRepository)
    {
        $this->zoneRepository = $zoneRepository;
    }


    public function getAllZones()
    {
        return $this->zoneRepository->findAll();
    }

    public function getZoneById($id)
    {
        $criteria = ['id' => $id];
        $result = $this->zoneRepository->findOneBy((array) $criteria);

        return $result;
    }

    public function getZoneBySlug($slug)
    {
        $criteria = ['slug' => $slug ,'status' => 1];
        $result = $this->zoneRepository->findOneBy((array) $criteria);

        return $result;
    }

    // zone active:
    public function getAllZonesActive()
    {
        $criteria = ['status' => 1];
        $result = $this->zoneRepository->findBy((array) $criteria);

        return $result;
    }

    public function getAllZonesActiveByAlphaOrder()
    {
        $criteria = ['status' => 1];
        $orderBy = ['name' => 'ASC'];
        $result = $this->zoneRepository->findBy((array) $criteria, (array) $orderBy);

        return $result;
    }
}
