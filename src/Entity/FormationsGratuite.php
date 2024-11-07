<?php

namespace App\Entity;

use App\Repository\FormationsGratuiteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationsGratuiteRepository::class)]
class FormationsGratuite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titregratuit = null;

    #[ORM\Column(length: 255)]
    private ?string $fichierpdfgratuit = null;

    #[ORM\Column]
    private ?int $nombreDePdf = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptionGratuit = null;

    #[ORM\Column(type: 'boolean')]
    private bool $publier = false;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $imaegratuit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videoGratuite = null;

    // Getters and Setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitregratuit(): ?string
    {
        return $this->titregratuit;
    }

    public function setTitregratuit(string $titregratuit): static
    {
        $this->titregratuit = $titregratuit;

        return $this;
    }

    public function getFichierpdfgratuit(): ?string
    {
        return $this->fichierpdfgratuit;
    }

    public function setFichierpdfgratuit(string $fichierpdfgratuit): static
    {
        $this->fichierpdfgratuit = $fichierpdfgratuit;

        return $this;
    }

    public function getNombreDePdf(): ?int
    {
        return $this->nombreDePdf;
    }

    public function setNombreDePdf(int $nombreDePdf): static
    {
        $this->nombreDePdf = $nombreDePdf;

        return $this;
    }

    public function getDescriptionGratuit(): ?string
    {
        return $this->descriptionGratuit;
    }

    public function setDescriptionGratuit(string $descriptionGratuit): static
    {
        $this->descriptionGratuit = $descriptionGratuit;

        return $this;
    }

    public function getPublier(): bool
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

    public function getImaegratuit(): ?string
    {
        return $this->imaegratuit;
    }

    public function setImaegratuit(string $imaegratuit): static
    {
        $this->imaegratuit = $imaegratuit;

        return $this;
    }

    public function getVideoGratuite(): ?string
    {
        return $this->videoGratuite;
    }

    public function setVideoGratuite(?string $videoGratuite): static
    {
        $this->videoGratuite = $videoGratuite;

        return $this;
    }
}
