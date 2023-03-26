<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use App\Repository\ContactRepository;
use App\Trait\IdEntityTrait;
use App\Trait\EnableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Fardus\Traits\Symfony\Entity\EmailEntity;
use Fardus\Traits\Symfony\Entity\EnableEntity;
use Fardus\Traits\Symfony\Entity\IdEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    use IdEntityTrait;
    use TimestampableEntity;
    use EnableEntityTrait;
    use EmailEntity;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private string $message;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $readAt = null;

    public function __construct()
    {
        $this->enable = true;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getReadAt(): ?DateTimeInterface
    {
        return $this->readAt;
    }

    public function setReadAt(?DateTimeInterface $readAt): self
    {
        $this->readAt = $readAt;

        return $this;
    }
}
