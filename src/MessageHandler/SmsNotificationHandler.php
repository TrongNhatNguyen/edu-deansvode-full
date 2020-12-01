<?php

namespace App\MessageHandler;

use App\Util\Helper\MailHelper;

use App\Message\SmsNotification;
use App\Service\Web\ContactService;
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

        $mailContent = $this->mailHelper->contentMailContact($data);
        $resultSendMail = $this->mailHelper->chooseMailType($mailContent, $mailType);

        if ($resultSendMail['status'] === 'success') {
            $this->contactService->updateUsercontact($data['idUserContact']);
        }
    }
}
