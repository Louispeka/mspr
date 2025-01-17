<?php

namespace App\Repository;

use App\Entity\DonneesVirus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DonneesVirus>
 *
 * @method DonneesVirus|null find($id, $lockMode = null, $lockVersion = null)
 * @method DonneesVirus|null findOneBy(array $criteria, array $orderBy = null)
 * @method DonneesVirus[]    findAll()
 * @method DonneesVirus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonneesVirusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DonneesVirus::class);
    }

    // Custom query methods if needed...
}
