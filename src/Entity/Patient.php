<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 */
class Patient 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Remplir le champ  prenom")
     * @Assert\Length(
     *      min = 5,
     *      max = 25,
     *      minMessage = "Le prénom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le prénom doit comporter au plus {{ limit }} caractères")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Remplir le champ  nom")
     * @Assert\Length(
     *      min = 5,
     *      max = 25,
     *      minMessage = "Le nom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le nom doit comporter au plus {{ limit }} caractères")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Remplir le champ  Cin")
     * @Assert\Length(
     *      min = 5,
     *      max = 10,
     *      minMessage = "Le nom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le nom doit comporter au plus {{ limit }} caractères")
     */
    private $cin;

    /**
     * @ORM\Column(type="date") 
     * @Assert\NotBlank(message = "Remplir le champ date")
     * 
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Remplir le champ  genre")
     */
    private $genre;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(message = "Remplir le champ Numero Telephone")
     * 
     * @Assert\Regex(
     *      pattern="/^[0-9]{10}+$/",
     *      message="numero telephone")
     */
    private $numTel;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Consultation", mappedBy="patients")
     */
    private $consultations;

    public function __construct()
    {
        $this->consultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(?string $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Consultation[]
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): self
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations[] = $consultation;
            $consultation->setPatients($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->contains($consultation)) {
            $this->consultations->removeElement($consultation);
            // set the owning side to null (unless already changed)
            if ($consultation->getPatients() === $this) {
                $consultation->setPatients(null);
            }
        }

        return $this;
    }

    // public function __toString()
    // {
    //     return $this->prenom;
    // }
}
