<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParkingRepository")
 */
class Parking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Residence", inversedBy="parkings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $residence;

    /**
     * @ORM\Column(type="integer")
     */
    private $num_place;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $situation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $occupation = false;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_blocage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $bloque_par;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Locataire", inversedBy="parking", cascade={"persist", "remove"})
     */
    private $locataire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResidence(): ?Residence
    {
        return $this->residence;
    }

    public function setResidence($residence)
    {
        $this->residence = $residence;

        return $this;
    }

    public function getNumPlace(): ?int
    {
        return $this->num_place;
    }

    public function setNumPlace($num_place)
    {
        $this->num_place = $num_place;

        return $this;
    }

    public function getSituation(): ?string
    {
        return $this->situation;
    }

    public function setSituation($situation)
    {
        $this->situation = $situation;

        return $this;
    }

    public function getDateBlocage(): ?\DateTimeInterface
    {
        return $this->date_blocage;
    }

    public function setDateBlocage($date_blocage)
    {
        $this->date_blocage = $date_blocage;

        return $this;
    }

    public function getBloquePar(): ?User
    {
        return $this->bloque_par;
    }

    public function setBloquePar($bloque_par)
    {
        $this->bloque_par = $bloque_par;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * @param mixed $occupation
     */
    public function setOccupation($occupation): void
    {
        $this->occupation = $occupation;
    }

    public function getLocataire(): ?Locataire
    {
        return $this->locataire;
    }

    public function setLocataire(?Locataire $locataire): self
    {
        $this->locataire = $locataire;

        return $this;
    }


}
