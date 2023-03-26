<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordEncoderInterface $passwordEncoder
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::provider() as $item) {
            $user = (new User());
            $user->setUsername($item['username'])
                ->setPassword($this->passwordEncoder->encodePassword($user, $item['password']))
                ->setEnable(true)
                ->setRoles($item['roles']);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private static function provider(): array
    {
        return [
            ['username' => 'fardus', 'password' => 'vupz66xx', 'roles' => ['ROLE_SUPER_ADMIN']],
        ];
    }
}
