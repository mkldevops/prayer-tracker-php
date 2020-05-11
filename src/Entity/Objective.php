<?php

namespace App\Entity;

use App\Repository\ObjectiveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Fardus\Traits\Symfony\Entity\EnableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ObjectiveRepository::class)
 * @UniqueEntity(fields={"program", "prayerName"})
 */
class Objective
{
    use EnableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Program::class, inversedBy="objectives")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Program $program = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $number = 1;

    /**
     * @ORM\ManyToOne(targetEntity=PrayerName::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?PrayerName $prayerName = null;

    /**
     * @ORM\OneToMany(targetEntity=Prayer::class, mappedBy="objective")
     */
    private ?Collection $prayers;

    public function __construct()
    {
        $this->enable = true;
        $this->prayers = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf("%s - %s", (string) $this->program, (string) $this->prayerName);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProgram(): ?Program
    {
        return $this->program;
    }

    public function setProgram(?Program $program): self
    {
        $this->program = $program;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
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

    /**
     * @return Collection|Prayer[]
     */
    public function getPrayers(): Collection
    {
        return $this->prayers;
    }

    public function addPrayer(Prayer $prayer): self
    {
        if (!$this->prayers->contains($prayer)) {
            $this->prayers[] = $prayer;
            $prayer->setProgram($this);
        }

        return $this;
    }

    public function removePrayer(Prayer $prayer): self
    {
        if ($this->prayers->contains($prayer)) {
            $this->prayers->removeElement($prayer);
            // set the owning side to null (unless already changed)
            if ($prayer->getProgram() === $this) {
                $prayer->setProgram(null);
            }
        }

        return $this;
    }

    public function getUser() : User
    {
        return $this->program->getUser();
    }
}
