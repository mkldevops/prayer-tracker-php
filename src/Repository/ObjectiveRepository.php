<?php

namespace App\Repository;

use App\Entity\Objective;
use App\Entity\Program;
use App\Exception\AppException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Objective|null find($id, $lockMode = null, $lockVersion = null)
 * @method Objective|null findOneBy(array $criteria, array $orderBy = null)
 * @method Objective[]    findAll()
 * @method Objective[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjectiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Objective::class);
    }

    /**
     * @throws AppException
     */
    public function sumNumberOfProgram(Program $program) : int
    {
        try {
            return (int) $this->createQueryBuilder('o')
                ->select('SUM(o.number) as number')
                ->where('o.program = :o_program')
                ->setParameter('o_program', $program)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException | NoResultException $e) {
            throw new AppException($e->getMessage(), 0, $e);
        }
    }
}
