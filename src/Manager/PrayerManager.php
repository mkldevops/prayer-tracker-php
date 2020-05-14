<?php

namespace App\Manager;

use App\Entity\Objective;
use App\Entity\Prayer;
use App\Entity\User;
use App\Exception\AppException;
use App\Repository\ObjectiveRepository;
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
    public ObjectiveRepository $objectiveRepository;
    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, PrayerRepository $prayerRepository, ObjectiveRepository $objectiveRepository)
    {
        $this->entityManager = $entityManager;
        $this->prayerRepository = $prayerRepository;
        $this->objectiveRepository = $objectiveRepository;
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

        $countObjective = $this->prayerRepository->count(['objective' => $objective, 'prayerName' => $objective->getPrayerName()]);
        $countProgram = $this->prayerRepository->countProgram($objective->getProgram());
        $numberProgram = $this->objectiveRepository->sumNumberOfProgram($objective->getProgram());

        return [
            'objective' => [
                'count' => $countObjective,
                'number' => $objective->getNumber(),
                'percent' => round(($countObjective / $objective->getNumber() * 100), 2),
                'sub' => $objective->getNumber() - $countObjective,
                'objective' => $objective->getId()
            ],
            'program' => [
                'count' => $countProgram,
                'number' => $numberProgram,
            ]
        ];
    }

    public function stats(Objective $objective, int $daysAgo)
    {
        $from = (new \DateTime())->setTimestamp(strtotime(sprintf("%d days ago", $daysAgo)));
        $current = clone $from;
        $data = [];
        while ($current->getTimestamp() <= time()) {
            $data[$current->format('d M')] = 0;
            $current->add(new \DateInterval('P1D'));
        }

        $result = $this->prayerRepository->statsOfObjective($objective, $from);
        foreach ($result as $item) {
            $date = (new \DateTime($item['date']))->format('d M');
            $data[$date] = $item['nb'];
        }

        return $data;
    }
}
