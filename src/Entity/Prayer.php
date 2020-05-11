<?php

namespace App\Entity;

use App\Repository\PrayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=PrayerRepository::class)
 */
class Prayer
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PrayerName::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?PrayerName $prayerName = null;

    /**
     * @ORM\ManyToOne(targetEntity=Objective::class, inversedBy="prayers")
     */
    private ?Objective $objective = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $accomplishedAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAccomplishedAt(): ?\DateTimeInterface
    {
        return $this->accomplishedAt;
    }

    public function setAccomplishedAt(?\DateTimeInterface $accomplishedAt): self
    {
        $this->accomplishedAt = $accomplishedAt;

        return $this;
    }
}
