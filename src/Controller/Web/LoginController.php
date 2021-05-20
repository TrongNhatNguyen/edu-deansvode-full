<?php

namespace App\Controller\Web;

use App\DTO\Request\LoginRequest;
use App\Service\Web\LoginService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginController extends AbstractController
{
    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }
    /**
     * @Route("/web/login", name="web_login")
     */
    public function login(Request $request, LoginRequest $loginRequest, ValidatorInterface $validator)
    {
        // dd($request->request->all());
        $loginRequest->buildByRequest($request);
        $errors = $validator->validate($loginRequest);
        // dd($errors);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            dd($messages);
            return $this->json([
                'notificate' => ['status' => 'failed', 'messages'  => $messages]
            ]);
        }
        // go to:
        return true;
    }
}
