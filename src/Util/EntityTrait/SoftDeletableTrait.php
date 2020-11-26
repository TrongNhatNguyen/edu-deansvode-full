<?php

namespace App\Util\EntityTrait;

use Doctrine\ORM\Mapping as ORM;

trait SoftDeletableTrait
{
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    public function setDeletedAt(\DateTime $datetime)
    {
        $this->deletedAt = $datetime;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt instanceof \DateTimeInterface;
    }

    public function recover()
    {
        $this->deletedAt = null;
    }
}
