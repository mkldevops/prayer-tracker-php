<?php

namespace App\Manager;

use App\Entity\Objective;
use App\Entity\PrayerName;
use App\Entity\Program;
use App\Exception\AppException;
use App\Repository\ObjectiveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Fardus\Traits\Symfony\Manager\LoggerTrait;
use Fardus\Traits\Symfony\Manager\SerializerTrait;

class ObjectiveManager
{
    use SerializerTrait;
    use LoggerTrait;

    public function __construct(public EntityManagerInterface $entityManager, public ObjectiveRepository $objectiveRepository)
    {
    }

    /**
     * @throws AppException
     */
    public function new(Program $program, PrayerName $prayerName, int $number): Objective
    {
        $exists = $this->objectiveRepository->count(['program' => $program, 'prayerName' => $prayerName]);
        if ($exists !== 0) {
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
