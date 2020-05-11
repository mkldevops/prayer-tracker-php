<?php

namespace App\Repository;

use App\Entity\Objective;
use App\Entity\Prayer;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Prayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prayer[]    findAll()
 * @method Prayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prayer::class);
    }

    public function statsOfObjective(Objective $objective, \DateTime $from)
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
            ->getResult();
    }
}
