<?php

declare(strict_types=1);

namespace App\Event\EntityListener;

use App\Entity\HasUserPropertyInterface;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDoctrineListener(event: Events::prePersist)]
final readonly class UserPropertyPersistDoctrineListener
{
    public function __construct(
        private Security $security
    ) {}

    public function __invoke(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof HasUserPropertyInterface
            && !$entity->getUser() instanceof User
            && ($user = $this->security->getUser()) instanceof User) {
            $entity->setUser($user);
        }
    }
}
