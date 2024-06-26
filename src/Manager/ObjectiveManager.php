<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Objective;
use App\Entity\PrayerName;
use App\Entity\Program;
use App\Exception\AppException;
use App\Repository\ObjectiveRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class ObjectiveManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ObjectiveRepository $objectiveRepository
    ) {}

    /**
     * @throws AppException
     */
    public function new(Program $program, PrayerName $prayerName, int $number): Objective
    {
        $exists = $this->objectiveRepository->count(['program' => $program, 'prayerName' => $prayerName]);
        if (0 !== $exists) {
            throw new AppException('This prayer exists on your objective');
        }

        $objective = new Objective();
        $objective->setProgram($program)
            ->setPrayerName($prayerName)
            ->setNumber($number)
        ;

        $this->entityManager->persist($objective);
        $this->entityManager->flush();

        return $objective;
    }
}
