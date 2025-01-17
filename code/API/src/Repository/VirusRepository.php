<?php

namespace App\Repository;

use App\Entity\Virus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Virus>
 *
 * @method Virus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Virus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Virus[]    findAll()
 * @method Virus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VirusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Virus::class);
    }

    //    /**
    //     * @return Virus[] Returns an array of Virus objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Virus
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
