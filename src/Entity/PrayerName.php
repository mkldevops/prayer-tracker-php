<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PrayerNameRepository;
use App\Trait\DescriptionEntityTrait;
use App\Trait\EnableEntityTrait;
use App\Trait\IdEntityTrait;
use App\Trait\NameEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Stringable;

#[ORM\Entity(repositoryClass: PrayerNameRepository::class)]
class PrayerName implements Stringable
{
    use DescriptionEntityTrait;
    use EnableEntityTrait;
    use IdEntityTrait;
    use NameEntityTrait;
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
