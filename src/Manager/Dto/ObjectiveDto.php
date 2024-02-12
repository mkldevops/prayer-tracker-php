<?php

declare(strict_types=1);

namespace App\Manager\Dto;

readonly class ObjectiveDto
{
    public function __construct(
        public int $count,
        public int $number,
        public float $percent,
        public int $sub,
        public int $objective,
    ) {}
}
