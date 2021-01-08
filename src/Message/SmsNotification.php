<?php

namespace App\Message;

class SmsNotification
{
    private $content;
    private $cons;

    public function __construct(object $content, int $cons)
    {
        $this->content = $content;
        $this->cons = $cons;
    }

    public function getContent(): object
    {
        return $this->content;
    }
    public function getCons(): int
    {
        return $this->cons;
    }
}
