<?php

namespace App\Service;

use App\Repository\CountryRepository;

class CountryService
{
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function getAllCountries()
    {
        $criteria = ['status' => 1];
        $result = $this->countryRepository->findBy((array) $criteria);

        return $result;
    }

    public function getAllCountriesByAlphabeticalOrder()
    {
        $criteria = ['status' => 1];
        $orderBy = ['name' => 'ASC'];
        $result = $this->countryRepository->findBy((array) $criteria, (array) $orderBy);

        return $result;
    }

    public function getCountryBySlug($slug)
    {
        $criteria = ['slug' => $slug, 'status' => 1];
        $result = $this->countryRepository->findOneBy((array) $criteria);

        return $result;
    }
}
