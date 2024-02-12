<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        foreach ($this->provider() as $item) {
            $user = (new User());
            $user->setUsername($item['username'])
                ->setPassword($this->hasher->hashPassword(
                    user: $user,
                    plainPassword: $item['password']
                ))
                ->setEnable(true)
                ->setRoles($item['roles'])
            ;
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return array<array<string, string|array<string>>
     */
    private function provider(): array
    {
        return [[
            'username' => 'fardus',
            'password' => 'vupz66xx',
            'roles' => ['ROLE_SUPER_ADMIN'],
        ]];
    }
}
