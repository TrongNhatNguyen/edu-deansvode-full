<?php

namespace App\Controller\Web;

use App\Service\Web\ContactService;
use App\DTO\Request\sendEmailRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Mail\ProceedSendMail;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private $contactService;

    public function __construct( ContactService $contactService )
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
     * @Route("/ccontact", name="web_contactt")
     */
    public function contactigm()
    {
        return $this->render('web/component/mailer/mail_content.html.twig',['fullName' => 'a', 'message' => 'b', 'email' =>'c']);
    }

    /**
     * @Route("/send-mail", name="send_email")
     */
    public function sendMail( Request $request, sendEmailRequest $sendEmailRequest, ValidatorInterface $validator, ProceedSendMail $proceedSendMail )
    {
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
        $mailData = [
            'subject' => 'DeansVote 2020 - Ask for advice and support!',
            'from' => 'deansvote@gmail.com',
            'to' => $data['email'],
            'body' => $this->renderView( 'email/mail_content.html.twig', [
                            'fullName' => $data['fullname'],
                            'email' => $data['email'],
                            'message' => $data['message']
                            ]),
            'body_type' => 'text/html'
        ];
        $mailType = ProceedSendMail::MAILER;
        $mailResult = $proceedSendMail->chooseMailType( $mailData, $mailType );
        // dd($mailResult);
        // =>.
        if ( $mailResult['status'] === 'success' ) {
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
