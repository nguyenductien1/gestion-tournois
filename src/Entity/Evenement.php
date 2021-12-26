<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */

class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomEv;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTermn;

    /**
     * @ORM\ManyToMany(targetEntity=TypeJeu::class, inversedBy="evenements")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Tournoi::class, mappedBy="ev")
     */
    private $tournois;

    public function __construct()
    {
        $this->type = new ArrayCollection();
        $this->tournois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEv(): ?string
    {
        return $this->nomEv;
    }

    public function setNomEv(string $nomEv): self
    {
        $this->nomEv = $nomEv;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateTermn(): ?\DateTimeInterface
    {
        return $this->dateTermn;
    }

    public function setDateTermn(\DateTimeInterface $dateTermn): self
    {
        $this->dateTermn = $dateTermn;

        return $this;
    }

    /**
     * @return Collection|TypeJeu[]
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(TypeJeu $type): self
    {
        if (!$this->type->contains($type)) {
            $this->type[] = $type;
        }

        return $this;
    }

    public function removeType(TypeJeu $type): self
    {
        $this->type->removeElement($type);

        return $this;
    }

    /**
     * @return Collection|Tournoi[]
     */
    public function getTournois(): Collection
    {
        return $this->tournois;
    }

    public function addTournoi(Tournoi $tournoi): self
    {
        if (!$this->tournois->contains($tournoi)) {
            $this->tournois[] = $tournoi;
            $tournoi->setEv($this);
        }

        return $this;
    }

    public function removeTournoi(Tournoi $tournoi): self
    {
        if ($this->tournois->removeElement($tournoi)) {
            // set the owning side to null (unless already changed)
            if ($tournoi->getEv() === $this) {
                $tournoi->setEv(null);
            }
        }

        return $this;
    }
}
