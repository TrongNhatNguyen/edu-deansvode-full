<?php

namespace App\Service\Country;

use App\Entity\Country;
use App\Service\Zone\ZoneFetcher;

class CountryCreator
{
    private $zoneFetcher;

    public function __construct(ZoneFetcher $zoneFetcher)
    {
        $this->zoneFetcher = $zoneFetcher;
    }


    public function fromRequest($request)
    {
        $data = (array) $request;

        return $this->fromArray($data);
    }

    public function fromArray(array $data)
    {
        $country = new Country();

        foreach ($data as $field => $value) {
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
