<?php

namespace App\Manager;

use DateInterval;
use App\Entity\Objective;
use App\Entity\Prayer;
use App\Exception\AppException;
use App\Repository\ObjectiveRepository;
use App\Repository\PrayerRepository;
use DateTime;
use Fardus\Traits\Symfony\Manager\EntityManagerTrait;
use Fardus\Traits\Symfony\Manager\LoggerTrait;
use Fardus\Traits\Symfony\Manager\SerializerTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class PrayerManager
{
    use SerializerTrait;
    use LoggerTrait;
    use EntityManagerTrait;

    public function __construct(public PrayerRepository $prayerRepository, public ObjectiveRepository $objectiveRepository)
    {
    }

    /**
     * @throws AppException
     * @return array{objective: array{count: int, number: int|null, percent: float, sub: int, objective: int|null}, program: array{count: int, number: int}}
     */
    public function add(Objective $objective, UserInterface $user): array
    {
        if ($objective->getPrayers()->count() >= $objective->getNumber()) {
            throw new AppException('the goal is achieved');
        }

        $prayer = new Prayer();
        $prayer->setUser($user)
            ->setObjective($objective)
            ->setAccomplishedAt(new DateTime())
            ->setPrayerName($objective->getPrayerName());

        $this->entityManager->persist($prayer);
        $this->entityManager->flush();

        $countObjective = $this->prayerRepository
            ->count(['objective' => $objective, 'prayerName' => $objective->getPrayerName()]);
        $countProgram = $this->prayerRepository->countProgram($objective->getProgram());
        $numberProgram = $this->objectiveRepository->sumNumberOfProgram($objective->getProgram());

        return [
            'objective' => [
                'count' => $countObjective,
                'number' => $objective->getNumber(),
                'percent' => round(($countObjective / $objective->getNumber() * 100), 2),
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
     * @throws AppException
     * @return array{delete: true}
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
