<?php

namespace App\DTO\Request\Area;

use App\DTO\Request\RequestDTOInterface;
use App\Validator\Admin\Constraints\Area as CustomAssert;
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

    /**
     * @Assert\NotBlank(
     *      message="Date updated is required!"
     * )
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     */
    public $updatedAt;


    public function __construct(Request $request)
    {
        $this->buildByRequest($request);
    }

    public function buildByRequest(Request $request)
    {
        $this->id = (int) $request->get('area_id', '');
        $this->status = (int) $request->get('area_status', 0);
        $this->updatedAt = new \DateTime($request->get('area_updated_at', 'now'));
    }
}
