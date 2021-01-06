<?php

namespace App\DTO\Request\Country;

use App\DTO\Request\RequestDTOInterface;
use App\Validator\Admin\Constraints\Country as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class UpdateStatusRequest implements RequestDTOInterface
{
    /**
     * @CustomAssert\Resource
     */
    public $id;

    /**
     * @Assert\NotBlank(
     *      message="Status is required!"
     * )
     * @Assert\type(type="integer")
     */
    public $status;

    public function __construct(Request $request)
    {
        $this->buildByRequest($request);
    }

    public function buildByRequest(Request $request)
    {
        $this->id = (int) $request->get('country_id', '');
        $this->status = (int) $request->get('country_status', 0);
    }
}
