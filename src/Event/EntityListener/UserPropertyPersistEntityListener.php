<?php

declare(strict_types=1);

namespace App\Event\EntityListener;

use App\Entity\HasUserPropertyInterface;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist)]
class UserPropertyPersistEntityListener
{
    public function __invoke(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof HasUserPropertyInterface && !$entity->getUser() instanceof User) {
            $entity->setUser($entity->getUser());
        }
    }
}
