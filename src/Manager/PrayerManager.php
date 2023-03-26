<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Objective;
use App\Entity\Prayer;
use App\Exception\AppException;
use App\Repository\ObjectiveRepository;
use App\Repository\PrayerRepository;
use DateInterval;
use DateTime;
use Fardus\Traits\Symfony\Manager\EntityManagerTrait;
use Fardus\Traits\Symfony\Manager\LoggerTrait;
use Fardus\Traits\Symfony\Manager\SerializerTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class PrayerManager
{
    use EntityManagerTrait;
    use LoggerTrait;
    use SerializerTrait;

    public function __construct(public PrayerRepository $prayerRepository, public ObjectiveRepository $objectiveRepository)
    {
    }

    /**
     * @return array{objective: array{count: int, number: null|int, percent: float, sub: int, objective: null|int}, program: array{count: int, number: int}}
     *
     * @throws AppException
     */
    public function add(Objective $objective, UserInterface $user): array
    {
        if ($objective->getPrayers()->count() >= $objective->getNumber()) {
            throw new AppException('the goal is achieved');
        }

        if (null === $objective->getProgram()) {
            throw new AppException('This objective have not program');
        }

        $prayer = new Prayer();
        $prayer->setUser($user)
            ->setObjective($objective)
            ->setAccomplishedAt(new DateTime())
            ->setPrayerName($objective->getPrayerName())
        ;

        $this->entityManager->persist($prayer);
        $this->entityManager->flush();

        $countObjective = $this->prayerRepository
            ->count(['objective' => $objective, 'prayerName' => $objective->getPrayerName()])
        ;
        $countProgram = $this->prayerRepository->countProgram($objective->getProgram());
        $numberProgram = $this->objectiveRepository->sumNumberOfProgram($objective->getProgram());

        return [
            'objective' => [
                'count' => $countObjective,
                'number' => $objective->getNumber(),
                'percent' => round($countObjective / $objective->getNumber() * 100, 2),
                'sub' => $objective->getNumber() - $countObjective,
                'objective' => $objective->getId(),
            ],
            'program' => [
                'count' => $countProgram,
                'number' => $numberProgram,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function stats(Objective $objective, int $daysAgo): array
    {
        $from = (new DateTime())->setTimestamp(strtotime(sprintf('%d days ago', $daysAgo)));
        $current = clone $from;
        $data = [];
        while ($current->getTimestamp() <= time()) {
            $data[$current->format('d M')] = 0;
            $current->add(new DateInterval('P1D'));
        }

        $result = $this->prayerRepository->statsOfObjective($objective, $from);
        foreach ($result as $item) {
            $date = DateTime::createFromFormat('Y-m-d', $item['date'])->format('d M');
            $data[$date] = $item['nb'];
        }

        return $data;
    }

    /**
     * @return array{delete: true}
     *
     * @throws AppException
     */
    public function delete(Prayer $prayer, ?UserInterface $user): array
    {
        if ($prayer->getUser() !== $user) {
            throw new AppException('acces denied for delete this item');
        }

        $this->entityManager->remove($prayer);
        $this->entityManager->flush();

        return [
            'delete' => true,
        ];
    }
}
