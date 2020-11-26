<?php

namespace App\Entity;

use App\Repository\DeanRepository;
use App\Util\EntityTrait\StatusTrait;
use App\Util\EntityTrait\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeanRepository::class)
 */
class Dean
{
    use StatusTrait;
    use TimestampTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $function;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getEmail1(): ?string
    {
        return $this->email1;
    }

    public function setEmail1(?string $email1): self
    {
        $this->email1 = $email1;

        return $this;
    }

    public function getEmail2(): ?string
    {
        return $this->email2;
    }

    public function setEmail2(?string $email2): self
    {
        $this->email2 = $email2;

        return $this;
    }

    public function getPassword1(): ?string
    {
        return $this->password1;
    }

    public function setPassword1(?string $password1): self
    {
        $this->password1 = $password1;

        return $this;
    }

    public function getPassword2(): ?string
    {
        return $this->password2;
    }

    public function setPassword2(?string $password2): self
    {
        $this->password2 = $password2;

        return $this;
    }

    public function getFunction(): ?string
    {
        return $this->function;
    }

    public function setFunction(?string $function): self
    {
        $this->function = $function;

        return $this;
    }

    public function getPhone1(): ?string
    {
        return $this->phone1;
    }

    public function setPhone1(?string $phone1): self
    {
        $this->phone1 = $phone1;

        return $this;
    }

    public function getPhone2(): ?string
    {
        return $this->phone2;
    }

    public function setPhone2(?string $phone2): self
    {
        $this->phone2 = $phone2;

        return $this;
    }
}
