<?php

namespace App\DTO\Request;

use App\Validator\Constraints as CustomAssert;
use Symfony\Component\HttpFoundation\Request;

class EmailRequest
{
    /**
     * @CustomAssert\EmailAddress
     */
    public $email;


    public function __construct()
    {
        return;
    }

    public function buildByRequest(Request $request)
    {
        $this->email = $request->request->get('email');
    }
}
