<?php

namespace App\MessageHandler;

use App\Util\Helper\MailHelper;

use App\Message\SmsUserConfirm;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SmsUserConfirmHandler implements MessageHandlerInterface
{
    private $mailHelper;

    public function __construct(
        MailHelper $mailHelper
    ) {
        $this->mailHelper = $mailHelper;
    }

    public function __invoke(SmsUserConfirm $message)
    {
        $data = $message->getContent();
        $mailType = $message->getCons();

        $mailContent = $this->mailHelper->contentMailUserConfirm($data);
        $this->mailHelper->chooseMailType($mailContent, $mailType);
    }
}
