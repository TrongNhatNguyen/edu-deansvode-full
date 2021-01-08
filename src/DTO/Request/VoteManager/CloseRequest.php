<?php

namespace App\DTO\Request\VoteManager;

use App\DTO\Request\RequestDTOInterface;
use App\Validator\Admin\Constraints\VoteManager as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class CloseRequest implements RequestDTOInterface
{
    /**
     * @CustomAssert\Resource
     */
    public $id;

    /**
     * @Assert\NotBlank(
     *      message="Active is required!"
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
        $this->id = (int) $request->get('vote_session_id', null);
        $this->status = (int) $request->get('status', 0);
    }
}
