<?php

namespace App\MessageHandler;

use App\Message\SmsMailTest;
use App\Util\Helper\MailHelper;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SmsMailTestHandler implements MessageHandlerInterface
{
    private $mailHelper;

    public function __construct(MailHelper $mailHelper)
    {
        $this->mailHelper = $mailHelper;
    }

    
    public function __invoke(SmsMailTest $message)
    {
        $sendTestRequest = $message->getContent();
        $mailType = $message->getCons();

        $mailContent = $this->mailHelper->contentMailTest($sendTestRequest);
        $this->mailHelper->chooseMailType($mailContent, $mailType);
    }
}
