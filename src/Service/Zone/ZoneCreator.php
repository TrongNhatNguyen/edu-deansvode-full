<?php

namespace App\Service\Zone;

use App\Entity\Zone;

class ZoneCreator
{
    public function fromRequest($request)
    {
        $data = (array) $request;

        return $this->fromArray($data);
    }

    public function fromArray(array $data)
    {
        $zone = new Zone();

        foreach ($data as $field => $value) {
            $setter = 'set' . $field;

            if (!method_exists($zone, $setter)) {
                continue;
            }

            $zone->$setter($value);
        }

        return $zone;
    }
}
