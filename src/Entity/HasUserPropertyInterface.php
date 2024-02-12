<?php

declare(strict_types=1);

namespace App\Entity;

interface HasUserPropertyInterface
{
    public function setUser(User $user): static;

    public function getUser(): ?User;
}
