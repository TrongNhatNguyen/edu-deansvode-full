<?php

namespace App\Entity;

use App\Repository\PalmRepository;
use App\Util\EntityTrait\SlugTrait;
use App\Util\EntityTrait\SortableTrait;
use App\Util\EntityTrait\StatusTrait;
use App\Util\EntityTrait\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PalmRepository::class)
 */
class Palm
{
    use SortableTrait;
    use StatusTrait;
    use TimestampTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
