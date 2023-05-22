<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[Vich\Uploadable]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Vich\UploadableField(mapping: 'projet_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $lien_visuel = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $lien_telechargement = null;

    #[ORM\Column]
    private ?bool $telechargement = null;

    #[ORM\Column]
    private ?bool $visuel = null;

    #[ORM\ManyToOne(inversedBy: 'type')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjetType $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

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
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getLienVisuel(): ?string
    {
        return $this->lien_visuel;
    }

    public function setLienVisuel(?string $lien_visuel): self
    {
        $this->lien_visuel = $lien_visuel;

        return $this;
    }

    public function getLienTelechargement(): ?string
    {
        return $this->lien_telechargement;
    }

    public function setLienTelechargement(?string $lien_telechargement): self
    {
        $this->lien_telechargement = $lien_telechargement;

        return $this;
    }

    public function getTelechargement(): ?bool
    {
        return $this->telechargement;
    }

    public function setTelechargement(bool $telechargement): self
    {
        $this->telechargement = $telechargement;

        return $this;
    }

    public function getVisuel(): ?bool
    {
        return $this->visuel;
    }

    public function setVisuel(bool $visuel): self
    {
        $this->visuel = $visuel;

        return $this;
    }

    public function getType(): ?ProjetType
    {
        return $this->type;
    }

    public function setType(?ProjetType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
