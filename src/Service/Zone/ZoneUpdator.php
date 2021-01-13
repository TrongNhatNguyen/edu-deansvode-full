<?php

namespace App\Service\Zone;

class ZoneUpdator
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

    public function fromArray($data)
    {
        $zone = $this->zoneFetcher->getZoneById($data['id']);

        foreach ($data as $field => $value) {
            if ($field === 'id') {
                continue;
            }

            $setter = 'set' . $field;

            if (!method_exists($zone, $setter)) {
                continue;
            }

            $zone->$setter($value);
        }

        return $zone;
    }
}
