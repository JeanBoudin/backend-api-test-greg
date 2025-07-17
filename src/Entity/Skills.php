<?php

namespace App\Entity;

use App\Repository\SkillsRepository;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SkillsRepository::class)]
#[Vich\Uploadable]
class Skills
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api.general'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api.general'])]
    private ?string $proficiency_level = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api.general'])]
    private ?string $icon_url = null;

    #[Vich\UploadableField(mapping: 'icon_custom', fileNameProperty: 'icon_url')]
    private ?File $iconFile = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['api.general'])]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, Works>
     */
    #[ORM\ManyToMany(targetEntity: Works::class, mappedBy: 'skills')]
    private Collection $works;

    public function __construct()
    {
        $this->works = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getProficiencyLevel(): ?string
    {
        return $this->proficiency_level;
    }

    public function setProficiencyLevel(string $proficiency_level): static
    {
        $this->proficiency_level = $proficiency_level;

        return $this;
    }

    public function getIconUrl(): ?string
    {
        return $this->icon_url;
    }

    public function setIconUrl(?string $icon_url): static
    {
        $this->icon_url = $icon_url;

        return $this;
    }

    public function setIconFile(?File $iconFile = null): void
    {
        $this->iconFile = $iconFile;

        if (null !== $iconFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getIconFile(): ?File
    {
        return $this->iconFile;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Works>
     */
    public function getWorks(): Collection
    {
        return $this->works;
    }

    public function addWork(Works $work): static
    {
        if (!$this->works->contains($work)) {
            $this->works->add($work);
            $work->addSkill($this);
        }

        return $this;
    }

    public function removeWork(Works $work): static
    {
        if ($this->works->removeElement($work)) {
            $work->removeSkill($this);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
