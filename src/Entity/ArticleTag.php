<?php

namespace App\Entity;

use App\Repository\ArticleTagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleTagRepository::class)]
class ArticleTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $TagArticle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTagArticle(): ?string
    {
        return $this->TagArticle;
    }

    public function setTagArticle(string $TagArticle): static
    {
        $this->TagArticle = $TagArticle;

        return $this;
    }
}
