<?php

namespace App\Service\Country;

use App\Repository\CountryRepository;

class CountryFetcher
{
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }


    public function getCountryById(int $id)
    {
        return $this->countryRepository->find($id);
    }

    public function getCountryByName($name)
    {
        return $this->countryRepository->findOneBy(['name' => $name]);
    }

    public function getCountryBySlug($slug)
    {
        return $this->countryRepository->findOneBy(['slug' => $slug]);
    }

    public function getCountryByIsoCode($isoCode)
    {
        return $this->countryRepository->findOneBy(['isoCode' => $isoCode]);
    }
}
