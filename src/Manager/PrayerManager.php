<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Objective;
use App\Entity\Prayer;
use App\Entity\Program;
use App\Exception\AppException;
use App\Manager\Dto\ObjectiveDto;
use App\Manager\Dto\ProgramDto;
use App\Repository\ObjectiveRepository;
use App\Repository\PrayerRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class PrayerManager
{
    public function __construct(
        private PrayerRepository $prayerRepository,
        private ObjectiveRepository $objectiveRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @return array{objective: ObjectiveDto, program: ProgramDto}
     *
     * @throws AppException
     */
    public function add(Objective $objective, UserInterface $user): array
    {
        if ($objective->getPrayers()->count() >= $objective->getNumber()) {
            throw new AppException('the goal is achieved');
        }

        if (!$objective->getProgram() instanceof Program) {
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
            'objective' => new ObjectiveDto(
                count: $countObjective,
                number: $objective->getNumber(),
                percent: round($countObjective / $objective->getNumber() * 100, 2),
                sub: $objective->getNumber() - $countObjective,
                objective: $objective->getId(),
            ),
            'program' => new ProgramDto(
                count: $countProgram,
                number: $numberProgram,
            ),
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
