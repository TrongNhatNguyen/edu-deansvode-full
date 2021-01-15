<?php

namespace App\DTO\Request\EmailTemplate;

use App\DTO\Request\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;

class SendTestRequest implements RequestDTOInterface
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
     * @CustomAssert\EmailAddress
     */
    public $recipient;


    public function __construct(Request $request)
    {
        $this->buildByRequest($request);
    }

    public function buildByRequest(Request $request)
    {
        $this->subject = $request->get('subject', '');
        $this->content = $request->get('content', '');
        $this->recipient = $request->get('recipient', '');
    }
}
