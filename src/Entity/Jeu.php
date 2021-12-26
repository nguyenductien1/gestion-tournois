<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\JeuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JeuRepository::class)
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
class Jeu
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
    private $nomJeu;

    /**
     * @ORM\ManyToOne(targetEntity=Poule::class, inversedBy="jeus")
     */
    private $poule;

    /**
     * @ORM\JoinTable(name="equipe_a_jeu")
     * @ORM\ManyToMany(targetEntity=Equipe::class)
     */
    private $equipeA;

    /**
     * @ORM\JoinTable(name="equipe_b_jeu")
     * @ORM\ManyToMany(targetEntity=Equipe::class)
     */
    private $equipeB;

    /**
     * @ORM\Column(type="integer")
     */
    private $pointEqA;

    /**
     * @ORM\Column(type="integer")
     */
    private $pointEqB;

   

    public function __construct()
    {
        $this->equipeA = new ArrayCollection();
        $this->equipeB = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomJeu(): ?string
    {
        return $this->nomJeu;
    }

    public function setNomJeu(string $nomJeu): self
    {
        $this->nomJeu = $nomJeu;

        return $this;
    }

    public function getPoule(): ?Poule
    {
        return $this->poule;
    }

    public function setPoule(?Poule $poule): self
    {
        $this->poule = $poule;

        return $this;
    }

    /**
     * @return Collection|Equipe[]
     */
    public function getEquipeA(): Collection
    {
        return $this->equipeA;
    }

    public function addEquipeA(Equipe $equipeA): self
    {
        if (!$this->equipeA->contains($equipeA)) {
            $this->equipeA[] = $equipeA;
        }

        return $this;
    }

    public function removeEquipeA(Equipe $equipeA): self
    {
        $this->equipeA->removeElement($equipeA);

        return $this;
    }

    /**
     * @return Collection|Equipe[]
     */
    public function getEquipeB(): Collection
    {
        return $this->equipeB;
    }

    public function addEquipeB(Equipe $equipeB): self
    {
        if (!$this->equipeB->contains($equipeB)) {
            $this->equipeB[] = $equipeB;
        }

        return $this;
    }

    public function removeEquipeB(Equipe $equipeB): self
    {
        $this->equipeB->removeElement($equipeB);

        return $this;
    }

    public function getPointEqA(): ?int
    {
        return $this->pointEqA;
    }

    public function setPointEqA(int $pointEqA): self
    {
        $this->pointEqA = $pointEqA;

        return $this;
    }

    public function getPointEqB(): ?int
    {
        return $this->pointEqB;
    }

    public function setPointEqB(int $pointEqB): self
    {
        $this->pointEqB = $pointEqB;

        return $this;
    }

    
}
