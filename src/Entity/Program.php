<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProgramRepository;
use App\Trait\EnableEntityTrait;
use App\Trait\IdEntityTrait;
use App\Trait\NameEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Stringable;

#[ORM\Entity(repositoryClass: ProgramRepository::class)]
class Program implements Stringable, HasUserPropertyInterface
{
    use EnableEntityTrait;
    use IdEntityTrait;
    use NameEntityTrait;
    use TimestampableEntity;

    #[ORM\OneToMany(mappedBy: 'program', targetEntity: Objective::class, orphanRemoval: true)]
    private Collection $objectives;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'programs')]
    private ?User $user = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $dayObjective = null;

    public function __construct()
    {
        $this->enable = true;
        $this->objectives = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Objective>
     */
    public function getObjectives(): Collection
    {
        return $this->objectives;
    }

    public function addObjective(Objective $objective): self
    {
        if (!$this->objectives->contains($objective)) {
            $this->objectives[] = $objective;
            $objective->setProgram($this);
        }

        return $this;
    }

    public function removeObjective(Objective $objective): self
    {
        if ($this->objectives->contains($objective)) {
            $this->objectives->removeElement($objective);
            // set the owning side to null (unless already changed)
            if ($objective->getProgram() === $this) {
                $objective->setProgram(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDayObjective(): ?int
    {
        return $this->dayObjective;
    }

    public function setDayObjective(int $dayObjective): self
    {
        $this->dayObjective = $dayObjective;

        return $this;
    }
}
