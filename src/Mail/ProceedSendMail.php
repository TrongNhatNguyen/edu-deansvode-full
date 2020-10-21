<?php

namespace App\Mail;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ProceedSendMail
{
    const SWIFT_MAILER = 0;
    const MAILER = 1;

    // private $swift_mailer;
    private $mailer;
    private $logger;

    public function __construct( MailerInterface $mailer, LoggerInterface $logger )
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }


    
    public function chooseMailType( $mailData, $type = self::MAILER )
    {
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
            $message = ( new Email() )
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
                'message' => 'Your idear successfuly sent!',
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