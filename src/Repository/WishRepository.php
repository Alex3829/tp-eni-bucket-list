<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Wish>
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    //    /**
    //     * @return Wish[] Returns an array of Wish objects
    //     */
    public function clearWishes(\DateTimeImmutable $dateLimit): int
    {

        return $this->createQueryBuilder('w')
            ->delete()
            ->where('w.dateCreated < :AGE_LIMIT')
            ->andWhere('w.isPublished = 0')
            ->setParameter('AGE_LIMIT', $dateLimit)
            ->getQuery()
            ->execute();
    }


    //    public function findOneBySomeField($value): ?Wish
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
