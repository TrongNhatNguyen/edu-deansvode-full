<?php

namespace App\DTO\Request\EmailTemplate;

use App\DTO\Request\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Validator\Admin\Constraints\EmailTemplate as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateRequest implements RequestDTOInterface
{
    /**
     * @CustomAssert\resource
     */
    public $id;

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
        $this->id = (int) $request->get('id', '');
        $this->subject = $request->get('subject', '');
        $this->content = $request->get('content', '');
        $this->recipient = $request->get('recipient', 'default@gmail.com');

        $this->sort = (int) $request->get('sort', 0);
        $this->updatedAt = new \DateTime($request->get('updated_at', 'now'));
    }
}
