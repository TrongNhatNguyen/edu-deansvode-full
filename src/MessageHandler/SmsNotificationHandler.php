<?php

namespace App\MessageHandler;

use App\Util\Helper\MailHelper;

use App\Message\SmsNotification;
use App\Service\ContactService;
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
        $sendEmailRequest = $message->getContent();
        $mailType = $message->getCons();

        $mailContent = $this->mailHelper->contentMailContact($sendEmailRequest);
        $resultSendMail = $this->mailHelper->chooseMailType($mailContent, $mailType);

        if ($resultSendMail['status'] === 'success') {
            $updateRequest = [
                'id' => $sendEmailRequest->idUserContact,
                'status' => 1,
                'updatedAt' => new \DateTime('now')
            ];

            $this->contactService->updateUsercontact($updateRequest);
        }
        sleep(1);
    }
}
