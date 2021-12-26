<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PouleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PouleRepository::class)
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
class Poule
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
    private $nomPoule;

    /**
     * @ORM\ManyToMany(targetEntity=Equipe::class, inversedBy="poules")
     */
    private $equipe;

    
    /**
     * @ORM\OneToMany(targetEntity=Jeu::class, mappedBy="poule")
     */
    private $jeus;

    /**
     * @ORM\ManyToOne(targetEntity=Tour::class, inversedBy="poules")
     */
    private $tour;


    public function __construct()
    {
        $this->equipe = new ArrayCollection();
        $this->jeus = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nomPoule;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPoule(): ?string
    {
        return $this->nomPoule;
    }

    public function setNomPoule(string $nomPoule): self
    {
        $this->nomPoule = $nomPoule;

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
     * @return Collection|Jeu[]
     */
    public function getJeus(): Collection
    {
        return $this->jeus;
    }

    public function addJeu(Jeu $jeu): self
    {
        if (!$this->jeus->contains($jeu)) {
            $this->jeus[] = $jeu;
            $jeu->setPoule($this);
        }

        return $this;
    }

    public function removeJeu(Jeu $jeu): self
    {
        if ($this->jeus->removeElement($jeu)) {
            // set the owning side to null (unless already changed)
            if ($jeu->getPoule() === $this) {
                $jeu->setPoule(null);
            }
        }

        return $this;
    }

    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    public function setTour(?Tour $tour): self
    {
        $this->tour = $tour;

        return $this;
    }

    
}
