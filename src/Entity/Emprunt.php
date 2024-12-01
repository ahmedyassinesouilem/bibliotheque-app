<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_emprunt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_retour = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    private ?Livre $livre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmprunt(): ?\DateTimeImmutable
    {
        return $this->date_emprunt;
    }

    public function setDateEmprunt(\DateTimeImmutable $date_emprunt): static
    {
        $this->date_emprunt = $date_emprunt;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeImmutable
    {
        return $this->date_retour;
    }

    public function setDateRetour(\DateTimeImmutable $date_retour): static
    {
        $this->date_retour = $date_retour;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): static
    {
        $this->livre = $livre;

        return $this;
    }
}
