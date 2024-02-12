<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PrayerRepository;
use App\Trait\IdEntityTrait;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Stringable;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PrayerRepository::class)]
class Prayer implements Stringable, HasUserPropertyInterface
{
    use IdEntityTrait;
    use TimestampableEntity;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: PrayerName::class)]
    private ?PrayerName $prayerName = null;

    #[ORM\ManyToOne(targetEntity: Objective::class, inversedBy: 'prayers')]
    private ?Objective $objective = null;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $accomplishedAt = null;

    public function __toString(): string
    {
        return sprintf('%s - %s', $this->prayerName, $this->user);
    }

    public function getPrayerName(): ?PrayerName
    {
        return $this->prayerName;
    }

    public function setPrayerName(?PrayerName $prayerName): self
    {
        $this->prayerName = $prayerName;

        return $this;
    }

    public function getObjective(): ?Objective
    {
        return $this->objective;
    }

    public function setObjective(?Objective $objective): self
    {
        $this->objective = $objective;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAccomplishedAt(): ?DateTimeInterface
    {
        return $this->accomplishedAt;
    }

    public function setAccomplishedAt(?DateTimeInterface $accomplishedAt): self
    {
        $this->accomplishedAt = $accomplishedAt;

        return $this;
    }
}
