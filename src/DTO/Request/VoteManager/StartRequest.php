<?php

namespace App\DTO\Request\VoteManager;

use App\DTO\Request\RequestDTOInterface;
use App\Validator\Admin\Constraints\VoteManager as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class StartRequest implements RequestDTOInterface
{
    /**
     * @CustomAssert\Year
     */
    public $year;

    /**
     * @Assert\NotBlank(
     *      message="Active is required!"
     * )
     * @Assert\type(type="integer")
     */
    public $status;

    /**
     * @Assert\NotBlank(
     *      message="'Check-mail' value is required!"
     * )
     * @Assert\type(type="integer")
     */
    public $checkSendMail;


    public function __construct(Request $request)
    {
        $this->buildByRequest($request);
    }

    public function buildByRequest(Request $request)
    {
        $this->year = $request->get('year', '');
        $this->status = (int) $request->get('status', 1);
        $this->checkSendMail = (int) $request->get('check_send_mail', 0);
    }
}
