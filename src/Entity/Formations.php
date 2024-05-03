<?php

namespace App\Entity;

use App\Repository\FormationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationsRepository::class)]
class Formations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fichierPDF = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $video = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null; 

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $dureeEnSecondes = null;

    #[ORM\Column]
    private ?int $nombreDePDF = null;

    #[ORM\Column(type: 'float')]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $titreDeLaFormation = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $publier = false;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFichierPDF(): ?string
    {
        return $this->fichierPDF;
    }

    public function setFichierPDF(string $fichierPDF): self
    {
        $this->fichierPDF = $fichierPDF;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDureeEnSecondes(): ?int
    {
        return $this->dureeEnSecondes;
    }

    public function setDureeEnSecondes(int $dureeEnSecondes): self
    {
        $this->dureeEnSecondes = $dureeEnSecondes;

        return $this;
    }

    public function getNombreDePDF(): ?int
    {
        return $this->nombreDePDF;
    }

    public function setNombreDePDF(int $nombreDePDF): self
    {
        $this->nombreDePDF = $nombreDePDF;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getTitreDeLaFormation(): ?string
    {
        return $this->titreDeLaFormation;
    }

    public function setTitreDeLaFormation(string $titreDeLaFormation): static
    {
        $this->titreDeLaFormation = $titreDeLaFormation;

        return $this;
    }

    public function getPublier(): ?bool
    {
        return $this->publier;
    }

    public function setPublier(bool $publier): static
    {
        $this->publier = $publier;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
