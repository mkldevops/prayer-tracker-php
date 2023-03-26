<?php

namespace App\Repository;

use App\Entity\PrayerName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrayerName>
 *
 * @method PrayerName|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrayerName|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrayerName[]    findAll()
 * @method PrayerName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrayerNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrayerName::class);
    }

    // /**
    //  * @return PrayerName[] Returns an array of PrayerName objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrayerName
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
