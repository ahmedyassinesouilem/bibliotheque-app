<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?int $isbn = null;

    #[ORM\Column]
    private ?bool $disponible = null;

    /**
     * @var Collection<int, Auteur>
     */
    #[ORM\ManyToMany(targetEntity: Auteur::class, inversedBy: 'livres')]
    private Collection $auteur;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    private ?Genre $genre = null;

    /**
     * @var Collection<int, Emprunt>
     */
    #[ORM\OneToMany(targetEntity: Emprunt::class, mappedBy: 'livre')]
    private Collection $emprunts;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    public function __construct()
    {
        $this->auteur = new ArrayCollection();
        $this->emprunts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getIsbn(): ?int
    {
        return $this->isbn;
    }

    public function setIsbn(int $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): static
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * @return Collection<int, Auteur>
     */
    public function getAuteur(): Collection
    {
        return $this->auteur;
    }

    public function addAuteur(Auteur $auteur): static
    {
        if (!$this->auteur->contains($auteur)) {
            $this->auteur->add($auteur);
        }

        return $this;
    }

    public function removeAuteur(Auteur $auteur): static
    {
        $this->auteur->removeElement($auteur);

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): static
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts->add($emprunt);
            $emprunt->setLivre($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): static
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getLivre() === $this) {
                $emprunt->setLivre(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
