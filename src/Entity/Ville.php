<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VilleRepository")
 */
class Ville
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $code_postal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Residence", mappedBy="ville", orphanRemoval=true)
     */
    private $residences;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CourtSejour", mappedBy="ville", orphanRemoval=true)
     */
    private $courtSejours;

    public function __construct()
    {
        $this->residences = new ArrayCollection();
        $this->courtSejours = new ArrayCollection();
    }

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

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    /**
     * @return Collection|Residence[]
     */
    public function getResidences(): Collection
    {
        return $this->residences;
    }

    public function addResidence(Residence $residence): self
    {
        if (!$this->residences->contains($residence)) {
            $this->residences[] = $residence;
            $residence->setVille($this);
        }

        return $this;
    }

    public function removeResidence(Residence $residence): self
    {
        if ($this->residences->contains($residence)) {
            $this->residences->removeElement($residence);
            // set the owning side to null (unless already changed)
            if ($residence->getVille() === $this) {
                $residence->setVille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CourtSejour[]
     */
    public function getCourtSejours(): Collection
    {
        return $this->courtSejours;
    }

    public function addCourtSejour(CourtSejour $courtSejour): self
    {
        if (!$this->courtSejours->contains($courtSejour)) {
            $this->courtSejours[] = $courtSejour;
            $courtSejour->setVille($this);
        }

        return $this;
    }

    public function removeCourtSejour(CourtSejour $courtSejour): self
    {
        if ($this->courtSejours->contains($courtSejour)) {
            $this->courtSejours->removeElement($courtSejour);
            // set the owning side to null (unless already changed)
            if ($courtSejour->getVille() === $this) {
                $courtSejour->setVille(null);
            }
        }

        return $this;
    }
}
