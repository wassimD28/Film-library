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
    private ?\DateTime $dhEmprunt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dhRetour = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    private ?Film $film = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    private ?Adherent $adherent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDhEmprunt(): ?\DateTime
    {
        return $this->dhEmprunt;
    }

    public function setDhEmprunt(\DateTime $dhEmprunt): static
    {
        $this->dhEmprunt = $dhEmprunt;

        return $this;
    }

    public function getDhRetour(): ?\DateTime
    {
        return $this->dhRetour;
    }

    public function setDhRetour(?\DateTime $dhRetour): static
    {
        $this->dhRetour = $dhRetour;

        return $this;
    }

    public function getFilm(): ?Film
    {
        return $this->film;
    }

    public function setFilm(?Film $film): static
    {
        $this->film = $film;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): static
    {
        $this->adherent = $adherent;

        return $this;
    }
}
