<?php

namespace App\DTO\Request\EmailTemplate;

use App\DTO\Request\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateRequest implements RequestDTOInterface
{
    /**
     * @Assert\NotBlank(
     *      message="Subject is required!"
     * )
     */
    public $subject;

    /**
     * @Assert\NotBlank(
     *      message="Content is required!"
     * )
     */
    public $content;

    /**
     * @Assert\Email(
     *      message="invalid recipient!"
     * )
     */
    public $recipient;

    /**
     * @Assert\NotBlank(
     *      message="Sort is required!"
     * )
     * @Assert\type(type="integer")
     */
    public $sort;

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


    public function __construct(Request $request)
    {
        $this->buildByRequest($request);
    }

    public function buildByRequest(Request $request)
    {
        $this->subject = $request->get('subject', '');
        $this->content = $request->get('content', '');
        $this->recipient = $request->get('recipient', 'default@gmail.com');

        $this->sort = (int) $request->get('sort', 0);
        $this->status = (int) $request->get('status', 1);
        $this->createdAt = new \DateTime($request->get('created_at', 'now'));
        $this->updatedAt = new \DateTime($request->get('updated_at', 'now'));
    }
}
