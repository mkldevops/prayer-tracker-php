<?php

namespace App\Manager;

use App\Entity\Program;
use App\Repository\PrayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Fardus\Traits\Symfony\Manager\LoggerTrait;
use Fardus\Traits\Symfony\Manager\SerializerTrait;

class ProgramManager
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

    public function stats(Program $program, int $daysAgo): array
    {
        $from = (new \DateTime())->setTimestamp(strtotime(sprintf('%d days ago', $daysAgo)));
        $current = clone $from;
        $data = [];
        while ($current->getTimestamp() <= time()) {
            foreach ($program->getObjectives()->toArray() as $objective) {
                $data[$objective->getPrayerName()->getName()][$current->format('d M')] = 0;
            }
            $current->add(new \DateInterval('P1D'));
        }

        $result = $this->prayerRepository->statsOfProgram($program, $from);

        foreach ($result as $item) {
            $date = (new \DateTime($item['date']))->format('d M');
            $data[$item['name']][$date] = (int) $item['nb'];
        }

        return $data;
    }
}
