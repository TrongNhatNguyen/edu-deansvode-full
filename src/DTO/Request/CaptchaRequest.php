<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CaptchaRequest
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Assert\NotBlank(
     *     message="string session is required."
     * )
     */
    public $sessionString;


    /**
     * @Assert\IdenticalTo(
     *     propertyPath="sessionString",
     *     message="secutity-code is valide, try again."
     * )
     */
    public $captchaString;


    public function buildByData($captchaString)
    {
        $this->captchaString = $captchaString;
        $this->sessionString = $this->session->get('code_captcha');
    }
}
