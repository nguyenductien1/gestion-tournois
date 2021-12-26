<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClubRepository::class)
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
class Club
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
    private $nomClub;

    /**
     * @ORM\OneToMany(targetEntity=Equipe::class, mappedBy="club")
     */
    private $equipes;

    public function __construct()
    {
        $this->equipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClub(): ?string
    {
        return $this->nomClub;
    }

    public function setNomClub(string $nomClub): self
    {
        $this->nomClub = $nomClub;

        return $this;
    }

    /**
     * @return Collection|Equipe[]
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipe $equipe): self
    {
        if (!$this->equipes->contains($equipe)) {
            $this->equipes[] = $equipe;
            $equipe->setClub($this);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): self
    {
        if ($this->equipes->removeElement($equipe)) {
            // set the owning side to null (unless already changed)
            if ($equipe->getClub() === $this) {
                $equipe->setClub(null);
            }
        }

        return $this;
    }
}
