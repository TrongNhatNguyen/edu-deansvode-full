<?php
namespace App\Service\Zone;

use App\Repository\ZoneRepository;

class ZoneFetcher
{
    private $zoneRepository;

    public function __construct(ZoneRepository $zoneRepository)
    {
        $this->zoneRepository = $zoneRepository;
    }


    public function getZoneById(int $id)
    {
        return $this->zoneRepository->find($id);
    }

    public function getZoneByName($name)
    {
        return $this->zoneRepository->findOneBy(['name' => $name]);
    }
    
    public function getZoneBySlug($slug)
    {
        return $this->zoneRepository->findOneBy(['slug' => $slug]);
    }

    public function getAllZones()
    {
        return $this->zoneRepository->findAll();
    }

    // zone active:
    public function getAllZonesActive()
    {
        return $this->zoneRepository->findBy(['status' => 1]);
    }

    public function getAllZonesActiveByAlphaOrder()
    {
        $criteria = ['status' => 1];
        $orderBy = ['name' => 'ASC'];

        return $this->zoneRepository->findBy($criteria, $orderBy);
    }
}
