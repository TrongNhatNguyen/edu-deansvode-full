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
     *      message="Date created is required!"
     * )
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     */
    public $beginAt;

    /**
     * @Assert\NotBlank(
     *      message="Active is required!"
     * )
     * @Assert\type(type="integer")
     */
    public $status;

    /**
     * @Assert\NotBlank(
     *      message="Date created is required!"
     * )
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     */
    public $createdAt;

    /**
     * @Assert\NotBlank(
     *      message="Date updated is required!"
     * )
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     */
    public $updatedAt;

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
        $this->year = (int) $request->get('year', '');
        $this->beginAt = new \DateTime($request->get('begin_at', 'now'));
        $this->status = (int) $request->get('status', 1);
        $this->checkSendMail = (int) $request->get('check_send_mail', 0);
        $this->createdAt = new \DateTime($request->get('created_at', 'now'));
        $this->updatedAt = new \DateTime($request->get('updated_at', 'now'));
    }
}
