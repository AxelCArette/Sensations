<?php

// src/Entity/Utilisateur.php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $Civilite = null; // Modification du type de données ici

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Prenom = null;

    #[ORM\Column]
    private ?int $NumeroDeTelephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Entreprise = null;

    #[ORM\Column(nullable: true)]
    private ?int $NumeroDeSiret = null;

    #[ORM\OneToMany(targetEntity: Adresse::class, mappedBy: 'User')]
    private Collection $adresses;

    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'Utilisateur')]
    private Collection $commandes;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column(length: 255)]
    private ?string $statutverifier = null;

    public function getNomComplet(): string
    {
        return $this->getNom().' '.$this->getPrenom();
    }

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCivilite(): ?string
    {
        return $this->Civilite;
    }

    public function setCivilite(?string $Civilite): static
    {
        $this->Civilite = $Civilite;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getNumeroDeTelephone(): ?int
    {
        return $this->NumeroDeTelephone;
    }

    public function setNumeroDeTelephone(int $NumeroDeTelephone): static
    {
        $this->NumeroDeTelephone = $NumeroDeTelephone;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->Entreprise;
    }

    public function setEntreprise(?string $Entreprise): static
    {
        $this->Entreprise = $Entreprise;

        return $this;
    }

    public function getNumeroDeSiret(): ?int
    {
        return $this->NumeroDeSiret;
    }

    public function setNumeroDeSiret(?int $NumeroDeSiret): static
    {
        $this->NumeroDeSiret = $NumeroDeSiret;

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): static
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setUser($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): static
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getUser() === $this) {
                $adress->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUtilisateur() === $this) {
                $commande->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getCommandeActuelle(): ?Commande
    {
        // Récupérer la liste des commandes de l'utilisateur
        $commandes = $this->getCommandes();

        // Vérifier si l'utilisateur a des commandes
        if ($commandes->isEmpty()) {
            // Si l'utilisateur n'a pas de commandes, retourner null
            return null;
        }

        // Trier les commandes par date de création décroissante
        $commandes = $commandes->toArray();
        usort($commandes, function (Commande $a, Commande $b) {
            return $b->getDateDeCreationCommande() <=> $a->getDateDeCreationCommande();
        });

        // Retourner la première commande (la plus récente)
        return $commandes[0];
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
{
    $this->token = $token;

    return $this;
}


    public function getStatutverifier(): ?string
    {
        return $this->statutverifier;
    }

    public function setStatutverifier(string $statutverifier): static
    {
        $this->statutverifier = $statutverifier;

        return $this;
    }
}
