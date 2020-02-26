<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeLogementTarifRepository")
 */
class TypeLogementTarif
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Residence", inversedBy="typeLogementTarifs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $residence;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $categorie;

    /**
     * @ORM\Column(type="float")
     */
    private $loyer;

    /**
     * @ORM\Column(type="float")
     */
    private $charges;

    /**
     * @ORM\Column(type="float")
     */
    private $cotis_acc;

    /**
     * @ORM\Column(type="float")
     */
    private $cotis_services;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResidence(): ?Residence
    {
        return $this->residence;
    }

    public function setResidence(?Residence $residence): self
    {
        $this->residence = $residence;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getLoyer(): ?float
    {
        return $this->loyer;
    }

    public function setLoyer(float $loyer): self
    {
        $this->loyer = $loyer;

        return $this;
    }

    public function getCharges(): ?float
    {
        return $this->charges;
    }

    public function setCharges(float $charges): self
    {
        $this->charges = $charges;

        return $this;
    }

    public function getCotisAcc(): ?float
    {
        return $this->cotis_acc;
    }

    public function setCotisAcc(float $cotis_acc): self
    {
        $this->cotis_acc = $cotis_acc;

        return $this;
    }

    public function getCotisServices(): ?float
    {
        return $this->cotis_services;
    }

    public function setCotisServices(float $cotis_services): self
    {
        $this->cotis_services = $cotis_services;

        return $this;
    }

    public function hasCategorie($categorie)
    {
        if($this->categorie === "$categorie")
        {
            return $this;
        }
    }
}
