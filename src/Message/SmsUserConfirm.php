<?php

namespace App\Message;

class SmsUserConfirm
{
    private $content;
    private $cons;

    public function __construct(array $content, int $cons)
    {
        $this->content = $content;
        $this->cons = $cons;
    }

    public function getContent(): array
    {
        return $this->content;
    }
    public function getCons(): int
    {
        return $this->cons;
    }
}
