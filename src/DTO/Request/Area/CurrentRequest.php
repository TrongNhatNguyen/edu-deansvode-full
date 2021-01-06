<?php

namespace App\DTO\Request\Area;

use App\DTO\Request\RequestDTOInterface;
use App\Validator\Admin\Constraints\Area as CustomAssert;
use Symfony\Component\HttpFoundation\Request;

class CurrentRequest implements RequestDTOInterface
{
    /**
     * @CustomAssert\Resource
     */
    public $id;

    public function __construct(Request $request)
    {
        $this->buildByRequest($request);
    }

    public function buildByRequest(Request $request)
    {
        $this->id = $request->get('area_id', '');
    }
}
