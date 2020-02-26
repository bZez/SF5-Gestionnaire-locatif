<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SepaRepository")
 */
class Sepa
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=34, nullable=true)
     */
    private $iban;

    /**
     * @ORM\Column(type="string", length=28, nullable=true)
     */
    private $rum;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $bic;

    /**
     * @ORM\Column(type="boolean", length=14, nullable=true)
     */
    private $titulaire;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $nom_titulaire;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $adr_titulaire;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $cp_titulaire;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $ville_titulaire;

    /**
     * @return mixed
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param mixed $iban
     */
    public function setIban($iban): void
    {
        $this->iban = $iban;
    }

    /**
     * @return mixed
     */
    public function getRum()
    {
        return $this->rum;
    }

    /**
     * @param mixed $rum
     */
    public function setRum($rum): void
    {
        $this->rum = $rum;
    }

    /**
     * @return mixed
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * @param mixed $bic
     */
    public function setBic($bic): void
    {
        $this->bic = $bic;
    }

    /**
     * @return mixed
     */
    public function getTitulaire()
    {
        return $this->titulaire;
    }

    /**
     * @param mixed $titulaire
     */
    public function setTitulaire($titulaire): void
    {
        $this->titulaire = $titulaire;
    }

    /**
     * @return mixed
     */
    public function getNomTitulaire()
    {
        return $this->nom_titulaire;
    }

    /**
     * @param mixed $nom_titulaire
     */
    public function setNomTitulaire($nom_titulaire): void
    {
        $this->nom_titulaire = $nom_titulaire;
    }

    /**
     * @return mixed
     */
    public function getAdrTitulaire()
    {
        return $this->adr_titulaire;
    }

    /**
     * @param mixed $adr_titulaire
     */
    public function setAdrTitulaire($adr_titulaire): void
    {
        $this->adr_titulaire = $adr_titulaire;
    }

    /**
     * @return mixed
     */
    public function getCpTitulaire()
    {
        return $this->cp_titulaire;
    }

    /**
     * @param mixed $cp_titulaire
     */
    public function setCpTitulaire($cp_titulaire): void
    {
        $this->cp_titulaire = $cp_titulaire;
    }

    /**
     * @return mixed
     */
    public function getVilleTitulaire()
    {
        return $this->ville_titulaire;
    }

    /**
     * @param mixed $ville_titulaire
     */
    public function setVilleTitulaire($ville_titulaire): void
    {
        $this->ville_titulaire = $ville_titulaire;
    }

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Locataire", inversedBy="sepa", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $locataire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocataire(): ?Locataire
    {
        return $this->locataire;
    }

    public function setLocataire(Locataire $locataire): self
    {
        $this->locataire = $locataire;

        return $this;
    }
}
