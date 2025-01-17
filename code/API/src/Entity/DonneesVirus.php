<?php

namespace App\Entity;

use App\Repository\DonneesVirusRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: DonneesVirusRepository::class)]
class DonneesVirus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_du_jour = null;

    #[ORM\Column]
    private ?int $total_cas = null;

    #[ORM\Column]
    private ?int $total_mort = null;

    #[ORM\Column]
    private ?int $nouveau_cas = null;

    #[ORM\Column]
    private ?int $nouveau_mort = null;

    #[ORM\ManyToOne(targetEntity: Pays::class)]
    #[ORM\JoinColumn(name: "ID_Pays", referencedColumnName: "id", nullable: false)]
    private ?Pays $pays = null;

    #[ORM\ManyToOne(targetEntity: Virus::class)]
    #[ORM\JoinColumn(name: "ID_Virus", referencedColumnName: "id", nullable: false)]
    private ?Virus $virus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getDateDuJour(): ?\DateTimeInterface
    {
        return $this->date_du_jour;
    }

    public function setDateDuJour(\DateTimeInterface $date_du_jour): static
    {
        $this->date_du_jour = $date_du_jour;
        return $this;
    }

    public function getTotalCas(): ?int
    {
        return $this->total_cas;
    }

    public function setTotalCas(int $total_cas): static
    {
        $this->total_cas = $total_cas;
        return $this;
    }

    public function getTotalMort(): ?int
    {
        return $this->total_mort;
    }

    public function setTotalMort(int $total_mort): static
    {
        $this->total_mort = $total_mort;
        return $this;
    }

    public function getNouveauCas(): ?int
    {
        return $this->nouveau_cas;
    }

    public function setNouveauCas(int $nouveau_cas): static
    {
        $this->nouveau_cas = $nouveau_cas;
        return $this;
    }

    public function getNouveauMort(): ?int
    {
        return $this->nouveau_mort;
    }

    public function setNouveauMort(int $nouveau_mort): static
    {
        $this->nouveau_mort = $nouveau_mort;
        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(Pays $pays): static
    {
        $this->pays = $pays;
        return $this;
    }

    public function getVirus(): ?Virus
    {
        return $this->virus;
    }

    public function setVirus(Virus $virus): static
    {
        $this->virus = $virus;
        return $this;
    }
}
