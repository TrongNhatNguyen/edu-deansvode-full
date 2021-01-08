<?php

namespace App\Controller\Web;

use App\DTO\Request\SendEmailRequest;

use App\Util\Helper\MailHelper;
use App\Message\SmsNotification;
use App\Service\Web\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * @Route("/send-mail-contact", name="send_email")
     */
    public function sendMail(SendEmailRequest $sendEmailRequest)
    {
        // validation:
        if (isset($sendEmailRequest->errors)) {
            return $this->json([
                'notificate' => ['status' => 'failed', 'messages' => $sendEmailRequest->errors]
            ]);
        }

        $result = $this->contactService->createUserContact($sendEmailRequest);
        
        // Send mail - use message & handler:
        $sendEmailRequest->idUserContact = $result['id'];
        $mailType = MailHelper::MAILER;
        $message = new SmsNotification($sendEmailRequest, $mailType);
        $this->dispatchMessage($message);

        return $this->json([
            'notificate' => $result
        ]);
    }
}
