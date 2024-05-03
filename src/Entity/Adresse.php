<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $AdresseComplete = null;

    #[ORM\Column(length: 255)]
    private ?string $Ville = null;

    #[ORM\Column]
    private ?int $CodePostale = null;

    #[ORM\Column(length: 255)]
    private ?string $Pays = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    private ?Utilisateur $User = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresseComplete(): ?string
    {
        return $this->AdresseComplete;
    }

    public function setAdresseComplete(string $AdresseComplete): static
    {
        $this->AdresseComplete = $AdresseComplete;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): static
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getCodePostale(): ?int
    {
        return $this->CodePostale;
    }

    public function setCodePostale(int $CodePostale): static
    {
        $this->CodePostale = $CodePostale;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->Pays;
    }

    public function setPays(string $Pays): static
    {
        $this->Pays = $Pays;

        return $this;
    }

    public function getUser(): ?Utilisateur
    {
        return $this->User;
    }

    public function setUser(?Utilisateur $User): static
    {
        $this->User = $User;

        return $this;
    }
}
