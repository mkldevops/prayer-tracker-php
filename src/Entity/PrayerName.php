<?php

namespace App\Entity;

use Stringable;
use App\Repository\PrayerNameRepository;
use App\Trait\EnableEntityTrait;
use App\Trait\IdEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Fardus\Traits\Symfony\Entity\DescriptionEntity;
use Fardus\Traits\Symfony\Entity\NameEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: PrayerNameRepository::class)]
class PrayerName implements Stringable
{
    use IdEntityTrait;
    use NameEntity;
    use DescriptionEntity;
    use EnableEntityTrait;
    use TimestampableEntity;

    public function __construct()
    {
        $this->enable = true;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}
