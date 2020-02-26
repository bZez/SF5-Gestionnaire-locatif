<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContratRepository")
 */
class Contrat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="date")
     */
    private $debut;

    /**
     * @ORM\Column(type="date")
     */
    private $fin;

    /**
     * @ORM\Column(type="date")
     */
    private $date_gen;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="contrats")
     */
    private $gen_by;

    /**
     * @ORM\Column(type="integer")
     */
    private $duree;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $pdf;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $universignTransId;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $universignSignId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Locataire", inversedBy="contrats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locataire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $signature = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_signature;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Logement", inversedBy="contrats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $logement;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $charges_perso;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $loyer_perso;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cotis_acc_perso;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cotis_services_perso;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sign_elec;

    public function __construct()
    {
        $this->date_gen = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(/*\DateTimeInterface*/ $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(/*\DateTimeInterface*/ $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getDateGen(): ?\DateTimeInterface
    {
        return $this->date_gen;
    }

    public function setDateGen(/*\DateTimeInterface*/ $date_gen): self
    {
        $this->date_gen = $date_gen;

        return $this;
    }

    public function getGenBy(): ?User
    {
        return $this->gen_by;
    }

    public function setGenBy(?User $gen_by): self
    {
        $this->gen_by = $gen_by;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUniversignTransId()
    {
        return $this->universignTransId;
    }

    /**
     * @param mixed $universignTransId
     */
    public function setUniversignTransId($universignTransId): void
    {
        $this->universignTransId = $universignTransId;
    }

    /**
     * @return mixed
     */
    public function getUniversignSignId()
    {
        return $this->universignSignId;
    }

    /**
     * @param mixed $universignSignId
     */
    public function setUniversignSignId($universignSignId): void
    {
        $this->universignSignId = $universignSignId;
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

    /**
     * @return mixed
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @param mixed $pdf
     */
    public function setPdf($pdf): void
    {
        $this->pdf = $pdf;
    }

    public function getSignature(): ?bool
    {
        return $this->signature;
    }

    public function setSignature(bool $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getDateSignature(): ?\DateTimeInterface
    {
        return $this->date_signature;
    }

    public function setDateSignature(/*?\DateTimeInterface*/ $date_signature)
    {
        $this->date_signature = $date_signature;

        return $this;
    }

    public function getLogement(): ?Logement
    {
        return $this->logement;
    }

    public function setLogement(?Logement $logement): self
    {
        $this->logement = $logement;

        return $this;
    }

    public function getChargesPerso(): ?float
    {
        return $this->charges_perso;
    }

    public function setChargesPerso(?float $charges_perso): self
    {
        $this->charges_perso = $charges_perso;

        return $this;
    }

    public function getLoyerPerso(): ?float
    {
        return $this->loyer_perso;
    }

    public function setLoyerPerso(?float $loyer_perso): self
    {
        $this->loyer_perso = $loyer_perso;

        return $this;
    }

    public function getCotisAccPerso(): ?float
    {
        return $this->cotis_acc_perso;
    }

    public function setCotisAccPerso(?float $cotis_acc_perso): self
    {
        $this->cotis_acc_perso = $cotis_acc_perso;

        return $this;
    }

    public function getCotisServicesPerso(): ?float
    {
        return $this->cotis_services_perso;
    }

    public function setCotisServicesPerso(?float $cotis_services_perso): self
    {
        $this->cotis_services_perso = $cotis_services_perso;

        return $this;
    }

    public function getLoyerTotalPerso()
    {
        return $this->getLoyerPerso() + $this->getChargesPerso() + $this->getCotisAccPerso() + $this->getCotisServicesPerso();
    }

    public function getSignElec(): ?bool
    {
        return $this->sign_elec;
    }

    public function setSignElec(?bool $sign_elec): self
    {
        $this->sign_elec = $sign_elec;

        return $this;
    }


}
