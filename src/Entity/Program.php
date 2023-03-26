<?php

namespace App\Entity;

use Stringable;
use App\Repository\ProgramRepository;
use App\Trait\IdEntityTrait;
use App\Trait\EnableEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Fardus\Traits\Symfony\Entity\NameEntityTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ProgramRepository::class)]
class Program implements Stringable
{
    use IdEntityTrait;
    use NameEntityTrait;
    use EnableEntityTrait;
    use TimestampableEntity;

    #[ORM\OneToMany(targetEntity: Objective::class, mappedBy: 'program', orphanRemoval: true)]
    private ?Collection $objectives;

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
     * @return Collection|Objective[]
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

    public function setUser(?UserInterface $user): self
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
