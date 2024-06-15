<?php

namespace App\Entity;

use App\Repository\EmailNewsletterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailNewsletterRepository::class)]
class EmailNewsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailpournewletter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $entreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sportifDeHautNiveau = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailpournewletter(): ?string
    {
        return $this->emailpournewletter;
    }

    public function setEmailpournewletter(?string $emailpournewletter): static
    {
        $this->emailpournewletter = $emailpournewletter;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(?string $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getSportifDeHautNiveau(): ?string
    {
        return $this->sportifDeHautNiveau;
    }

    public function setSportifDeHautNiveau(?string $sportifDeHautNiveau): static
    {
        $this->sportifDeHautNiveau = $sportifDeHautNiveau;

        return $this;
    }

}
