<?php

namespace App\Service\Country;

use App\Service\Zone\ZoneFetcher;

class CountryUpdator
{
    private $countryFetcher;
    private $zoneFetcher;

    public function __construct(CountryFetcher $countryFetcher, ZoneFetcher $zoneFetcher)
    {
        $this->countryFetcher = $countryFetcher;
        $this->zoneFetcher = $zoneFetcher;
    }

    public function fromRequest($request)
    {
        $data = (array) $request;

        return $this->fromArray($data);
    }

    public function fromArray(array $data)
    {
        $country = $this->countryFetcher->getCountryById($data['id']);
        
        foreach ($data as $field => $value) {
            if ($field === 'id') {
                continue;
            }

            if ($field === 'zoneId') {
                $field = 'zone';
                $value = $this->zoneFetcher->getZoneById($value);
            }

            $setter = 'set' . $field;

            if (!method_exists($country, $setter)) {
                continue;
            }

            $country->$setter($value);
        }

        return $country;
    }
}
