<?php

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest
{
    private $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    /**
     * @Assert\NotBlank(
     *      message="user-name is required."
     * )
     */
    public $userName;


    /**
     * @Assert\NotBlank(
     *      message="password is required."
     * )
     */
    public $password;


    /**
     */
    public $sessionString;


    /**
     * @Assert\NotBlank(
     *      message="security-code is required."
     * )
     * @Assert\IdenticalTo(
     *     propertyPath="sessionString",
     *     message="secutity-code is valide, try again."
     * )
     */
    public $captchaString;

    

    public function buildByRequest(Request $request)
    {
        $this->userName = $request->request->get('username');
        $this->password = $request->request->get('password');
        $this->captchaString = $request->request->get('captcha_string');
        $this->sessionString = $this->session->get('code_captcha');
    }
}
