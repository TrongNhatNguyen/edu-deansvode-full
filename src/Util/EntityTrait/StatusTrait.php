<?php

namespace App\Util\EntityTrait;

use Doctrine\ORM\Mapping as ORM;

trait StatusTrait
{
    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }
}
