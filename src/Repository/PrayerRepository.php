<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Objective;
use App\Entity\Prayer;
use App\Entity\Program;
use App\Exception\AppException;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Fardus\Traits\Symfony\Manager\LoggerTrait;

/**
 * @extends ServiceEntityRepository<Prayer>
 *
 * @method null|Prayer find($id, $lockMode = null, $lockVersion = null)
 * @method null|Prayer findOneBy(array $criteria, array $orderBy = null)
 * @method Prayer[]    findAll()
 * @method Prayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrayerRepository extends ServiceEntityRepository
{
    use LoggerTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prayer::class);
    }

    public function statsOfObjective(Objective $objective, DateTime $from): array
    {
        return $this->createQueryBuilder('p')
            ->select('DATE(p.createdAt) as date')
            ->addSelect('count(p) AS nb')
            ->where('p.objective = :p_objective')
            ->andWhere('p.createdAt >= :p_createdAt')
            ->setParameter('p_objective', $objective)
            ->setParameter('p_createdAt', $from)
            ->groupBy('date')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws AppException
     */
    public function countProgram(Program $program): int
    {
        try {
            return (int) $this->createQueryBuilder('p')
                ->select('COUNT(p) as count')
                ->innerJoin('p.objective', 'o')
                ->where('o.program = :o_program')
                ->setParameter('o_program', $program)
                ->getQuery()
                ->getSingleScalarResult()
            ;
        } catch (NonUniqueResultException|NoResultException $e) {
            throw new AppException($e->getMessage(), 0, $e);
        }
    }

    public function statsOfProgram(Program $program, DateTime $from): array
    {
        return $this->createQueryBuilder('p')
            ->select('DATE(p.createdAt) as date')
            ->addSelect('pn.name')
            ->addSelect('count(p) AS nb')
            ->innerJoin('p.objective', 'o')
            ->innerJoin('p.prayerName', 'pn')
            ->where('o.program = :o_program')
            ->andWhere('p.createdAt >= :p_createdAt')
            ->setParameter('o_program', $program)
            ->setParameter('p_createdAt', $from)
            ->groupBy('date', 'pn.name')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws AppException
     */
    public function countDay(Program $program, DateTime $from, DateTime $until)
    {
        try {
            return (int) $this->createQueryBuilder('p')
                ->select('COUNT(p) as count')
                ->innerJoin('p.objective', 'o')
                ->where('o.program = :o_program')
                ->andWhere('p.createdAt BETWEEN :from AND :until')
                ->setParameter('from', $from)
                ->setParameter('until', $until)
                ->setParameter('o_program', $program)
                ->getQuery()
                ->getSingleScalarResult()
            ;
        } catch (NonUniqueResultException|NoResultException $e) {
            throw new AppException($e->getMessage(), 0, $e);
        }
    }
}
