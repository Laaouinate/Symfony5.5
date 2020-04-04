<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ConsultationRepository")
 */
class Consultation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message = "Remplir le champ  date")
     * 
     */
    private $dateRdv;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Remplir le champ  description")
     * 
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Remplir le champ  realisation")
     * @Assert\Length(
     *      min = 3,
     *      max = 3,
     *      exactMessage="le champs doit avoir {{ limit }} caractères")
     * 
     */
    private $realisation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="consultations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patients;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRdv(): ?\DateTimeInterface
    {
        return $this->dateRdv;
    }

    public function setDateRdv(\DateTimeInterface $dateRdv): self
    {
        $this->dateRdv = $dateRdv;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRealisation(): ?string
    {
        return $this->realisation;
    }

    public function setRealisation(string $realisation): self
    {
        $this->realisation = $realisation;

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

    public function getPatients(): ?Patient
    {
        return $this->patients;
    }

    public function setPatients(?Patient $patients): self
    {
        $this->patients = $patients;

        return $this;
    }
}
