<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NiveauJouerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NiveauJouerRepository::class)
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
class NiveauJouer
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
    private $niveau;

    /**
     * @ORM\OneToMany(targetEntity=Jouer::class, mappedBy="niveau")
     */
    private $jouer;

    public function __construct()
    {
        $this->jouer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * @return Collection|Jouer[]
     */
    public function getJouer(): Collection
    {
        return $this->jouer;
    }

    public function addJouer(Jouer $jouer): self
    {
        if (!$this->jouer->contains($jouer)) {
            $this->jouer[] = $jouer;
            $jouer->setNiveau($this);
        }

        return $this;
    }

    public function removeJouer(Jouer $jouer): self
    {
        if ($this->jouer->removeElement($jouer)) {
            // set the owning side to null (unless already changed)
            if ($jouer->getNiveau() === $this) {
                $jouer->setNiveau(null);
            }
        }

        return $this;
    }
}
