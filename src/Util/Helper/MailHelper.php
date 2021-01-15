<?php

namespace App\Util\Helper;

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


    public function chooseMailType($mailContent, $type = self::MAILER)
    {
        switch ($type) {
            case self::MAILER:
                return $this->sendByMailer($mailContent);
                break;

            default:
                # code...
                break;
        }
    }

    public function sendByMailer($mailContent)
    {
        try {
            $message = (new Email())
                ->from($mailContent['from'])
                ->to($mailContent['to'])
                ->subject($mailContent['subject'])
                ->html($mailContent['body'], $mailContent['body_type']);
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
            $this->logger->info('Email not send: ' . $ex->getMessage());
            return [
                'status' => 'failed',
                'message' => 'mailing failed',
                'error' => [ get_class($ex) => $ex->getMessage() ]
            ];
        }
    }


    ///======================= content email:
    public function contentMailContact($sendEmailRequest)
    {
        return [
            'subject' => 'DeansVote 2020 - Ask for advice and support!',
            'from' => $sendEmailRequest->email,
            'to' => $_ENV['CONTACT_MAIL'],
            'body' => $this->twig->render('email/mail_contact.html.twig', [
                            'fullName' => $sendEmailRequest->fullName,
                            'email' => $sendEmailRequest->email,
                            'message' => $sendEmailRequest->message
                        ]),
            'body_type' => 'text/html'
        ];
    }

    public function contentMailUserConfirm($data)
    {
        return [
            'subject' => 'DeansVote 2020 - confirm your account!',
            'from' => $_ENV['CONFIRM_MAIL'],
            'to' => $data['email1'],
            'body' => $this->twig->render('email/mail_user_confirm.html.twig', [
                            'firstName' => $data['firstName'],
                            'email' => $data['email1'],
                            'password' => $data['password1']
                            ]),
            'body_type' => 'text/html'
        ];
    }

    public function contentMailStartCampaign($infoDean)
    {
        return [
            'subject' => 'DeansVote 2020 - new vote session already start!',
            'from' => $_ENV['START_NEW_CAMPAIGN_MAIL'],
            'to' => $infoDean['email1'],
            'body' => $this->twig->render('email/mail_start_campaign.html.twig', ['infoDean' => $infoDean]),
            'body_type' => 'text/html'
        ];
    }

    public function contentMailTest($sendTestRequest)
    {
        return [
            'subject' => $sendTestRequest->subject,
            'from' => $_ENV['MAIL_TEST_MARKETING'],
            'to' => $sendTestRequest->recipient,
            'body' => $sendTestRequest->content,
            'body_type' => 'text/html'
        ];
    }
}
