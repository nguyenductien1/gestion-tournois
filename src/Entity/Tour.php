<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TourRepository::class)
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
//             "security_post_denormalize" => "is_granted('ROLE_ADMIN')",
//             "security_post_denormalize_message" => "Role admin uniquement.",
//         ],
//         "delete" => [
//             "security_post_denormalize" => "is_granted('ROLE_ADMIN')",
//             "security_post_denormalize_message" => "Role admin uniquement.",
//         ],
//         "patch" => [
//             "security_post_denormalize" => "is_granted('ROLE_ADMIN')",
//             "security_post_denormalize_message" => "Role admin uniquement.",
//         ],
//     ],
// )]
class Tour
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
    private $nomTour;

    /**
     * @ORM\ManyToOne(targetEntity=Tournoi::class, inversedBy="tours")
     */
    private $tournoi;

    /**
     * @ORM\ManyToMany(targetEntity=Equipe::class, inversedBy="tours")
     */
    private $equipe;

    /**
     * @ORM\OneToMany(targetEntity=Poule::class, mappedBy="tour")
     */
    private $poules;

    public function __construct()
    {
        $this->equipe = new ArrayCollection();
        $this->poules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTour(): ?string
    {
        return $this->nomTour;
    }

    public function setNomTour(string $nomTour): self
    {
        $this->nomTour = $nomTour;

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
     * @return Collection|Equipe[]
     */
    public function getEquipe(): Collection
    {
        return $this->equipe;
    }

    public function addEquipe(Equipe $equipe): self
    {
        if (!$this->equipe->contains($equipe)) {
            $this->equipe[] = $equipe;
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): self
    {
        $this->equipe->removeElement($equipe);

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
            $poule->setTour($this);
        }

        return $this;
    }

    public function removePoule(Poule $poule): self
    {
        if ($this->poules->removeElement($poule)) {
            // set the owning side to null (unless already changed)
            if ($poule->getTour() === $this) {
                $poule->setTour(null);
            }
        }

        return $this;
    }
}
