<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoeuRepository")
 */
class Voeu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint",length=4)
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $souhait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Logement", inversedBy="voeux")
     * @ORM\JoinColumn(nullable=false)
     */
    private $logement;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * @param mixed $annee
     */
    public function setAnnee($annee): void
    {
        $this->annee = $annee;
    }


    public function getSouhait(): ?string
    {
        return $this->souhait;
    }

    public function setSouhait(string $souhait): self
    {
        $this->souhait = $souhait;

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


}
