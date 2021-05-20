<?php

namespace App\Helper;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailHelper
{
    const SWIFT_MAILER = 0;
    const MAILER = 1;

    // private $swift_mailer;
    private $twig;
    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger, Environment $twig)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }


    public function chooseMailType($data, $type = self::MAILER)
    {
        $mailData = [
            'subject' => 'DeansVote 2020 - Ask for advice and support!',
            'from' => $data['email'],
            'to' => $_ENV['CONTACT_MAIL'],
            'body' => $this->twig->render('email/mail_content.html.twig', [
                            'fullName' => $data['full_name'],
                            'email' => $data['email'],
                            'message' => $data['message']
                            ]),
            'body_type' => 'text/html'
        ];
        switch ($type) {
            case self::MAILER:
                return $this->sendByMailer($mailData);
                break;

            default:
                # code...
                break;
        }
    }

    public function sendByMailer($mailData)
    {
        try {
            $message = (new Email())
                ->from($mailData['from'])
                ->to($mailData['to'])
                ->subject($mailData['subject'])
                ->html($mailData['body'], $mailData['body_type']);
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
            $this->mailer->send($message);
            $this->logger->info('Email sent!');
            return [
                'status' => 'success',
                'message' => 'email successfuly sent!',
            ];
        } catch (\Exception $ex) {
            $this->logger->info('Email not send!');
            return [
                'status' => 'failed',
                'message' => 'mailing failed',
                'error' => [ get_class($ex) => $ex->getMessage() ]
            ];
        }
    }
}
