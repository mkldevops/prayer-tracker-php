<?php

namespace App\Manager;

use App\Entity\Objective;
use App\Entity\Prayer;
use App\Entity\User;
use App\Exception\AppException;
use App\Repository\PrayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Fardus\Traits\Symfony\Manager\SerializerTrait;
use Fardus\Traits\Symfony\Manager\LoggerTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class PrayerManager
{
    use SerializerTrait;
    use LoggerTrait;

    public PrayerRepository $prayerRepository;
    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, PrayerRepository $prayerRepository)
    {
        $this->entityManager = $entityManager;
        $this->prayerRepository = $prayerRepository;
    }

    /**
     * @throws AppException
     */
    public function add(Objective $objective, UserInterface $user)
    {
        if($objective->getPrayers()->count() >= $objective->getNumber()) {
            throw new AppException('the goal is achieved');
        }

        $prayer = new Prayer();
        $prayer->setUser($user)
            ->setObjective($objective)
            ->setAccomplishedAt(new \DateTime())
            ->setPrayerName($objective->getPrayerName())
        ;

        $this->entityManager->persist($prayer);
        $this->entityManager->flush();

        return [
            'count' => $this->prayerRepository->count(['objective' => $objective, 'prayerName' => $objective->getPrayerName()]),
            'number' => $objective->getNumber(),
            'objective' => $objective->getId()
        ];
    }
}
