<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?int $birthday = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?int $phone = null;

    /**
     * @var Collection<int, Analyse>
     */
    #[ORM\ManyToMany(targetEntity: Analyse::class, inversedBy: 'patients')]
    private Collection $patientAnalyse;

    public function __construct()
    {
        $this->patientAnalyse = new ArrayCollection();
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthday(): ?int
    {
        return $this->birthday;
    }

    public function setBirthday(int $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Analyse>
     */
    public function getPatientAnalyse(): Collection
    {
        return $this->patientAnalyse;
    }

    public function addPatientAnalyse(Analyse $patientAnalyse): static
    {
        if (!$this->patientAnalyse->contains($patientAnalyse)) {
            $this->patientAnalyse->add($patientAnalyse);
        }

        return $this;
    }

    public function removePatientAnalyse(Analyse $patientAnalyse): static
    {
        $this->patientAnalyse->removeElement($patientAnalyse);

        return $this;
    }
}
