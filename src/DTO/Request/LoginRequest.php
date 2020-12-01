<?php

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest
{
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


    public function buildByRequest(Request $request)
    {
        $this->userName = $request->request->get('username');
        $this->password = $request->request->get('password');
    }
}
