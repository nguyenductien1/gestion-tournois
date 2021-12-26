<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipeRepository::class)
 */
// #[ApiResource(
//     attributes: ["security" => "is_granted('ROLE_USER')"],
//     collectionOperations: [
//         "post" => [
//             "security" => "is_granted('ROLE_ADMIN')",
//             "security_message" => "Role admin peut créer cette resources uniquement.",
//         ],
//         "get" => [
//             "security" => "is_granted('PUBLIC_ACCESS')",
//             "security_message" => "Tous le monde peut acéder à ces resources.",
//         ],
//     ],
//     itemOperations: [
//         "get" => [
//             "security" => "is_granted('PUBLIC_ACCESS')",
//             "security_message" => "Tous le monnde peut acéder à cette resource.",
//         ],
//         "put" => [
//             "security_post_denormalize" => "is_granted('ROLE_ADMIN'))",
//             "security_post_denormalize_message" => "Role admin uniquement.",
//         ],
//         "delete" => [
//             "security_post_denormalize" => "is_granted('ROLE_ADMIN'))",
//             "security_post_denormalize_message" => "Role admin uniquement.",
//         ],
//         "patch" => [
//             "security_post_denormalize" => "is_granted('ROLE_ADMIN'))",
//             "security_post_denormalize_message" => "Role admin uniquement.",
//         ],
//     ],
// )]
class Equipe
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
    private $nomEquipe;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="equipes")
     */
    private $club;

    /**
     * @ORM\ManyToMany(targetEntity=Tour::class, mappedBy="equipe")
     */
    private $tours;

    /**
     * @ORM\ManyToOne(targetEntity=Tournoi::class, inversedBy="equipes")
     */
    private $tournoi;

    /**
     * @ORM\ManyToMany(targetEntity=Poule::class, mappedBy="equipe")
     */
    private $poules;

    /**
     * @ORM\OneToMany(targetEntity=Jouer::class, mappedBy="equipe")
     */
    private $jouers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $niveauEquipe;

    public function __construct()
    {
        $this->tours = new ArrayCollection();
        $this->poules = new ArrayCollection();
        $this->jouers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nomEquipe;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nomEquipe;
    }

    public function setNomEquipe(string $nomEquipe): self
    {
        $this->nomEquipe = $nomEquipe;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    /**
     * @return Collection|Tour[]
     */
    public function getTours(): Collection
    {
        return $this->tours;
    }

    public function addTour(Tour $tour): self
    {
        if (!$this->tours->contains($tour)) {
            $this->tours[] = $tour;
            $tour->addEquipe($this);
        }

        return $this;
    }

    public function removeTour(Tour $tour): self
    {
        if ($this->tours->removeElement($tour)) {
            $tour->removeEquipe($this);
        }

        return $this;
    }

    public function getTournoi(): ?Tournoi
    {
        return $this->tournoi;
    }

    public function setTournoi(?Tournoi $tournoi): self
    {
        $this->tournoi = $tournoi;

        return $this;
    }

    /**
     * @return Collection|Poule[]
     */
    public function getPoules(): Collection
    {
        return $this->poules;
    }

    public function addPoule(Poule $poule): self
    {
        if (!$this->poules->contains($poule)) {
            $this->poules[] = $poule;
            $poule->addEquipe($this);
        }

        return $this;
    }

    public function removePoule(Poule $poule): self
    {
        if ($this->poules->removeElement($poule)) {
            $poule->removeEquipe($this);
        }

        return $this;
    }

    /**
     * @return Collection|Jouer[]
     */
    public function getJouers(): Collection
    {
        return $this->jouers;
    }

    public function addJouer(Jouer $jouer): self
    {
        if (!$this->jouers->contains($jouer)) {
            $this->jouers[] = $jouer;
            $jouer->setEquipe($this);
        }

        return $this;
    }

    public function removeJouer(Jouer $jouer): self
    {
        if ($this->jouers->removeElement($jouer)) {
            // set the owning side to null (unless already changed)
            if ($jouer->getEquipe() === $this) {
                $jouer->setEquipe(null);
            }
        }

        return $this;
    }

    public function getNiveauEquipe(): ?string
    {
        return $this->niveauEquipe;
    }

    public function setNiveauEquipe(?string $niveauEquipe): self
    {
        $this->niveauEquipe = $niveauEquipe;

        return $this;
    }

    
}
