<?php

namespace App\Controller\Web;

use App\DTO\Request\ReCaptchaRequest;
use App\Service\Web\ContactService;
use App\DTO\Request\SendEmailRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Helper\MailHelper;
use App\Message\SmsNotification;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * @Route("/#contact", name="web_contact")
     */
    public function contact()
    {
        return true;
    }

    /**
     * @Route("/send-mail", name="send_email")
     */
    public function sendMail(
        Request $request,
        SendEmailRequest $sendEmailRequest,
        ReCaptchaRequest  $reCaptchaRequest,
        ValidatorInterface $validator
    ) {
        // validate data form:
        $sendEmailRequest->buildByRequest($request);
        $errors = $validator->validate($sendEmailRequest);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return $this->json([
                'notificate' => ['status' => 'failed', 'messages' => $messages]
            ]);
        }

        $data = $request->request->all();
        $data['status'] = 0; // default
        $data['receiver'] = '';

        // go to get data -> db:
        $result = $this->contactService->createUserContact($data);
        
        // go to send mail - use message & handler:
        $data['idUserContact'] = $result['id'];
        $mailType = MailHelper::MAILER;
        $message = new SmsNotification($data, $mailType);
        $this->dispatchMessage($message);

        return $this->json([
            'notificate' => $result
        ]);
    }
}
