<?php

namespace App\MessageHandler;

use App\Helper\MailHelper;

use App\Message\SmsNotification;
use App\Service\Web\ContactService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SmsNotificationHandler implements MessageHandlerInterface
{
    private $mailHelper;
    private $contactService;

    public function __construct(
        MailHelper $mailHelper,
        ContactService $contactService
    ) {
        $this->mailHelper = $mailHelper;
        $this->contactService = $contactService;
    }

    public function __invoke(SmsNotification $message)
    {
        $data = $message->getContent();
        $mailType = $message->getCons();

        $resultSendMail = $this->mailHelper->chooseMailType($data, $mailType);

        if ($resultSendMail['status'] === 'success') {
            $this->contactService->updateUsercontact($data['idUserContact']);
        }
    }
}
