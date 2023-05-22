<?php

namespace App\Entity;

use App\Repository\CompetenceTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompetenceTypeRepository::class)]
class CompetenceType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Competence::class, orphanRemoval: true)]
    private Collection $type;

    public function __construct()
    {
        $this->type = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Competence>
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(Competence $type): self
    {
        if (!$this->type->contains($type)) {
            $this->type->add($type);
            $type->setType($this);
        }

        return $this;
    }

    public function removeType(Competence $type): self
    {
        if ($this->type->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getType() === $this) {
                $type->setType(null);
            }
        }

        return $this;
    }
}
