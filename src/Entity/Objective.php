<?php

declare(strict_types=1);

namespace App\Entity;

use Stringable;
use App\Repository\ObjectiveRepository;
use App\Trait\EnableEntityTrait;
use App\Trait\IdEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ObjectiveRepository::class)]
#[ORM\UniqueConstraint(fields: ['program', 'prayerName'])]
#[UniqueEntity(fields: ['program', 'prayerName'], errorPath: 'port', message: 'This port is already in use on that host.')]
class Objective implements Stringable
{
    use EnableEntityTrait;
    use IdEntityTrait;
    use TimestampableEntity;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Program::class, inversedBy: 'objectives')]
    private ?Program $program = null;

    #[ORM\Column(type: 'integer')]
    private ?int $number = 1;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: PrayerName::class)]
    private ?PrayerName $prayerName = null;

    #[ORM\OneToMany(targetEntity: Prayer::class, mappedBy: 'objective')]
    private ?Collection $prayers;

    public function __construct()
    {
        $this->enable = true;
        $this->prayers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('%s - %s', (string) $this->program, (string) $this->prayerName);
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

    public function getUser(): User
    {
        return $this->program->getUser();
    }
}
