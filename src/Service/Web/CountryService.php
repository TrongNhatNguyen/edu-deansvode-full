<?php

namespace App\Service\Web;

use App\Repository\CountryRepository;
use App\Repository\ZoneRepository;

class CountryService
{
    private $countryRepository;
    private $zoneRepository;

    public function __construct(CountryRepository $countryRepository, ZoneRepository $zoneRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->zoneRepository = $zoneRepository;
    }

    
    public function getAllCountries()
    {
        $result = $this->countryRepository->findAll();

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

    // active:
    public function getAllCountriesActive()
    {
        $criteria = ['status' => 1];
        $result = $this->countryRepository->findBy((array) $criteria);

        return $result;
    }
    
    public function getAllCountriesActiveByAlphaOrder()
    {
        $criteria = ['status' => 1];
        $orderBy = ['name' => 'ASC'];
        $result = $this->countryRepository->findBy((array) $criteria, (array) $orderBy);

        return $result;
    }
}
