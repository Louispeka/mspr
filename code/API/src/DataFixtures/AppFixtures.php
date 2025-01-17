<?php

namespace App\DataFixtures;

use App\Entity\Pays;
use App\Entity\Virus;
use App\Entity\DonneesVirus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create sample Pays
        $pays1 = new Pays();
        $pays1->setLibelle('France');
        $pays1->setCodeLettre('FR');
        $pays1->setCodeChiffre('250');
        $manager->persist($pays1);

        $pays2 = new Pays();
        $pays2->setLibelle('United States');
        $pays2->setCodeLettre('US');
        $pays2->setCodeChiffre('840');
        $manager->persist($pays2);

        // Create sample Virus
        $virus1 = new Virus();
        $virus1->setLibelle('Coronavirus');
        $virus1->setDateApparition(new \DateTime('2019-12-01'));
        $virus1->setDateFin(new \DateTime('2023-12-01'));
        $virus1->setDescription('A novel coronavirus causing respiratory illness.');
        $manager->persist($virus1);

        $virus2 = new Virus();
        $virus2->setLibelle('Influenza');
        $virus2->setDateApparition(new \DateTime('1918-01-01'));
        $virus2->setDateFin(new \DateTime('1919-12-01'));
        $virus2->setDescription('A highly contagious viral infection.');
        $manager->persist($virus2);

        // Create sample DonneesVirus
        $donneesVirus1 = new DonneesVirus();
        $donneesVirus1->setDateDuJour(new \DateTime('2023-01-01'));
        $donneesVirus1->setTotalCas(1000000);
        $donneesVirus1->setTotalMort(50000);
        $donneesVirus1->setNouveauCas(10000);
        $donneesVirus1->setNouveauMort(500);
        $donneesVirus1->setPays($pays1);
        $donneesVirus1->setVirus($virus1);
        $manager->persist($donneesVirus1);

        $donneesVirus2 = new DonneesVirus();
        $donneesVirus2->setDateDuJour(new \DateTime('2023-01-02'));
        $donneesVirus2->setTotalCas(2000000);
        $donneesVirus2->setTotalMort(100000);
        $donneesVirus2->setNouveauCas(20000);
        $donneesVirus2->setNouveauMort(1000);
        $donneesVirus2->setPays($pays2);
        $donneesVirus2->setVirus($virus2);
        $manager->persist($donneesVirus2);

        // Flush data to the database
        $manager->flush();
    }
}
