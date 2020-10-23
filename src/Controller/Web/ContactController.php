<?php

namespace App\Controller\Web;

use App\Service\Web\ContactService;
use App\DTO\Request\SendEmailRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Hepler\MailHepler;

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
        ValidatorInterface $validator,
        MailHepler $mailHepler
    ) {
        // validate data:
        $sendEmailRequest->buildByRequest($request);
        $errors = $validator->validate($sendEmailRequest);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return $this->json([
                'notificate' => [ 'status' => 'failed', 'messages' => $messages ]
            ]);
        }
        // go to send mail:
        $data = $request->request->all();
        $mailType = MailHepler::MAILER;
        $mailResult = $mailHepler->chooseMailType($data, $mailType);
        // =>.
        if ($mailResult['status'] === 'success') {
            return $this->json([
                'notificate' => $mailResult
            ]);
        } else {
            return $this->json([
                'notificate' => $mailResult
            ]);
        }
    }
}
