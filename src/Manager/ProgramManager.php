<?php

declare(strict_types=1);

namespace App\Manager;

use DateTime;
use DateInterval;
use App\Entity\Program;
use App\Exception\AppException;
use App\Repository\PrayerRepository;
use Fardus\Traits\Symfony\Manager\EntityManagerTrait;
use Fardus\Traits\Symfony\Manager\LoggerTrait;
use Fardus\Traits\Symfony\Manager\SerializerTrait;

class ProgramManager
{
    use EntityManagerTrait;
    use LoggerTrait;
    use SerializerTrait;

    public function __construct(public PrayerRepository $prayerRepository)
    {
    }

    public function stats(Program $program, int $daysAgo): array
    {
        $from = (new DateTime())->setTimestamp(strtotime(sprintf('%d days ago', $daysAgo)));
        $current = clone $from;
        $data = [];
        while ($current->getTimestamp() <= time()) {
            foreach ($program->getObjectives()->toArray() as $objective) {
                $data[$objective->getPrayerName()->getName()][$current->format('d M')] = 0;
            }
            $current->add(new DateInterval('P1D'));
        }

        $result = $this->prayerRepository->statsOfProgram($program, $from);

        foreach ($result as $item) {
            $date = DateTime::createFromFormat('Y-m-d', $item['date'])->format('d M');
            $data[$item['name']][$date] = (int) $item['nb'];
        }

        return $data;
    }

    /**
     * @return array{countDay: mixed}
     *
     * @throws AppException
     */
    public function countDay(Program $program): array
    {
        $countDay = $this->prayerRepository->countDay($program, new DateTime('today'), new DateTime('tomorrow'));

        return ['countDay' => $countDay];
    }
}
