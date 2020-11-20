<?php

namespace App\Service;

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
        $criteria = ['status' => 1];
        $result = $this->zoneRepository->findBy((array) $criteria);

        return $result;
    }

    public function getAllZonesByAlphabeticalOrder()
    {
        $criteria = ['status' => 1];
        $orderBy = ['name' => 'ASC'];
        $result = $this->zoneRepository->findBy((array) $criteria, (array) $orderBy);

        return $result;
    }

    public function getZoneBySlug($slug)
    {
        $criteria = ['slug' => $slug ,'status' => 1];
        $result = $this->zoneRepository->findOneBy((array) $criteria);

        return $result;
    }
}
