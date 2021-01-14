<?php

namespace App\DTO\Request\EmailTemplate;

use App\DTO\Request\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Validator\Admin\Constraints\EmailTemplate as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

class LoadTempRequest implements RequestDTOInterface
{
    /**
     * @CustomAssert\resource
     */
    public $id;

    /**
     * @Assert\NotBlank(
     *      message="Active is required!"
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
        $this->id = (int) $request->get('id', null);
        $this->status = (int) $request->get('status', 1);
        $this->updatedAt = new \DateTime($request->get('updated_at', 'now'));
    }
}
