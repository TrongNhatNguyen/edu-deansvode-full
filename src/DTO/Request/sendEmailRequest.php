<?php

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class sendEmailRequest
{
    /**
     * @Assert\NotBlank(
     *      message="Full-Name is required."
     * )
     */
    public $fullName;
    
    
    /**
     * @Assert\NotBlank(
     *      message="Email is required."
     * )
     * @Assert\Email(
     *      message="Email is invalid."
     * )
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

    public function __construct()
    {}

    public function buildByRequest(Request $request)
    {
        $this->fullName = $request->request->get('fullname');
        $this->email = $request->request->get('email');
        $this->institution = $request->request->get('institution');
        $this->position = $request->request->get('position');
        $this->message = $request->request->get('message');
    }

}