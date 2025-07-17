<?php

namespace App\Entity;

use App\Repository\EducationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EducationRepository::class)]
class Education
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
    private ?string $degree = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api.general'])]
    private ?string $institution = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api.general'])]
    private ?string $period = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api.general'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api.general'])]
    private ?string $diploma = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api.general'])]
    private ?string $specification = null;

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

    public function getDegree(): ?string
    {
        return $this->degree;
    }

    public function setDegree(string $degree): static
    {
        $this->degree = $degree;

        return $this;
    }

    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    public function setInstitution(string $institution): static
    {
        $this->institution = $institution;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getDiploma(): ?string
    {
        return $this->diploma;
    }

    public function setDiploma(string $diploma): static
    {
        $this->diploma = $diploma;

        return $this;
    }

    public function getSpecification(): ?string
    {
        return $this->specification;
    }

    public function setSpecification(string $specification): static
    {
        $this->specification = $specification;

        return $this;
    }
}
