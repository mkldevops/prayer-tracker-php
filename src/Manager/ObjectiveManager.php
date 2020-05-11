<?php

namespace App\Manager;

use App\Entity\Objective;
use App\Entity\PrayerName;
use App\Entity\Program;
use App\Repository\ObjectiveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Fardus\Traits\Symfony\Manager\SerializerTrait;
use Fardus\Traits\Symfony\Manager\LoggerTrait;

class ObjectiveManager
{
    use SerializerTrait;
    use LoggerTrait;

    public ObjectiveRepository $objectiveRepository;
    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ObjectiveRepository $objectiveRepository)
    {
        $this->entityManager = $entityManager;
        $this->objectiveRepository = $objectiveRepository;
    }

    public function new(Program $program, PrayerName $prayerName, int $number) : Objective
    {
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
