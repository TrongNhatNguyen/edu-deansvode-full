<?php

namespace App\MessageHandler;

use App\Message\SmsMailStartCampaign;
use App\Util\Helper\MailHelper;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SmsMailStartCampaignHandler implements MessageHandlerInterface
{
    private $mailHelper;

    public function __construct(MailHelper $mailHelper)
    {
        $this->mailHelper = $mailHelper;
    }

    public function __invoke(SmsMailStartCampaign $message)
    {
        $infoDeans = $message->getContent();
        $mailType = $message->getCons();

        foreach ($infoDeans as $infoDean) {
            $mailContent = $this->mailHelper->contentMailStartCampaign($infoDean);
            $this->mailHelper->chooseMailType($mailContent, $mailType);
            sleep(1);
        }
    }
}
