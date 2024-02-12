<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Program;
use App\Exception\AppException;
use App\Repository\PrayerRepository;
use DateInterval;
use DateTime;

readonly class ProgramManager
{
    public function __construct(
        public PrayerRepository $prayerRepository
    ) {}

    /**
     * @return array<string, array<string, int>>
     *
     * @throws AppException
     */
    public function stats(Program $program, int $daysAgo): array
    {
        $from = (new DateTime())->setTimestamp((int) strtotime(sprintf('%d days ago', $daysAgo)));
        $current = clone $from;
        $data = [];

        while ($current->getTimestamp() <= time()) {
            foreach ($program->getObjectives() as $objective) {
                $data[$objective->getPrayerName()?->getName()][$current->format('d M')] = 0;
            }

            $current->add(new DateInterval('P1D'));
        }

        $result = $this->prayerRepository->statsOfProgram($program, $from);

        foreach ($result as $item) {
            $date = DateTime::createFromFormat('Y-m-d', (string) $item['date']);
            if (false === $date) {
                throw new AppException('Invalid date');
            }

            $format = $date->format('d M');
            $data[(string) $item['name']][$format] = (int) $item['nb'];
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
