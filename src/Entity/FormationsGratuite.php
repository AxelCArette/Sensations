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
    private ?int $NombreDePdf = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $DescriptionGratuit = null;

    #[ORM\Column]
    private ?int $publier = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $imaegratuit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $VideoGratuite = null;

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
        return $this->NombreDePdf;
    }

    public function setNombreDePdf(int $NombreDePdf): static
    {
        $this->NombreDePdf = $NombreDePdf;

        return $this;
    }

    public function getDescriptionGratuit(): ?string
    {
        return $this->DescriptionGratuit;
    }

    public function setDescriptionGratuit(string $DescriptionGratuit): static
    {
        $this->DescriptionGratuit = $DescriptionGratuit;

        return $this;
    }

    public function getPublier(): ?int
    {
        return $this->publier;
    }

    public function setPublier(int $publier): static
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
        return $this->VideoGratuite;
    }

    public function setVideoGratuite(?string $VideoGratuite): static
    {
        $this->VideoGratuite = $VideoGratuite;

        return $this;
    }
}
