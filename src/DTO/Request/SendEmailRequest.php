<?php

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;

class SendEmailRequest implements RequestDTOInterface
{
    /**
     * @CustomAssert\FullName
     */
    public $fullName;
    
    /**
     * @CustomAssert\EmailAddress
     */
    public $email;


    /**
     * @Assert\NotBlank(
     *      message="Academic Institution is required."
     * )
     */
    public $institution;


    /**
     * @Assert\NotBlank(
     *      message="Postition is required."
     * )
     */
    public $position;


    /**
     * @Assert\NotBlank(
     *      message="Message is required."
     * )
     */
    public $message;

    /**
     * @Assert\NotBlank(
     *      message="Active is required!"
     * )
     * @Assert\type(type="integer")
     */
    public $status;

    /**
     * @Assert\Type(
     *      type="string",
     *      message="Receiver cannot be a number."
     * )
     */
    public $receiver;

    /**
     * @CustomAssert\ReCaptcha
     */
    public $reCaptchaString;


    public function __construct(Request $request)
    {
        $this->buildByRequest($request);
    }

    public function buildByRequest(Request $request)
    {
        $this->fullName = $request->get('full_name', '');
        $this->email = (string) $request->get('email', '');
        $this->institution = $request->get('institution', '');
        $this->position = $request->get('position', '');
        $this->message = $request->get('message', '');
        $this->status = (int) $request->get('status', 0);
        $this->receiver = $request->get('receiver', null);

        $this->reCaptchaString = $request->get('g-recaptcha-response', '');
    }
}
