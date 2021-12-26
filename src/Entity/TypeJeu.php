<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TypeJeuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeJeuRepository::class)
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
class TypeJeu
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
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Evenement::class, mappedBy="type")
     */
    private $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->addType($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            $evenement->removeType($this);
        }

        return $this;
    }
}
