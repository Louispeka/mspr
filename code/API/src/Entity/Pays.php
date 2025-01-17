<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaysRepository::class)]
class Pays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 10, unique: true)]
    private ?string $code_lettre = null;

    #[ORM\Column(length: 10, unique: true)]
    private ?string $code_chiffre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCodeLettre(): ?string
    {
        return $this->code_lettre;
    }

    public function setCodeLettre(string $code_lettre): static
    {
        $this->code_lettre = $code_lettre;

        return $this;
    }

    public function getCodeChiffre(): ?string
    {
        return $this->code_chiffre;
    }

    public function setCodeChiffre(string $code_chiffre): static
    {
        $this->code_chiffre = $code_chiffre;

        return $this;
    }
}
