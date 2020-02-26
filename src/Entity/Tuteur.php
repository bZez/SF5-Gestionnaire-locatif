<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TuteurRepository")
 */
class Tuteur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180,nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=180,nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="string", length=180,nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=180,nullable=true)
     */
    private $cpl_adresse;

    /**
     * @ORM\Column(type="string", length=180,nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=8,nullable=true)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=15,nullable=true)
     */
    private $tel_fixe;

    /**
     * @ORM\Column(type="string", length=15,nullable=true)
     */
    private $tel_mobile;

    /**
     * @ORM\Column(type="string", length=50,nullable=true)
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getDateNaissance()
    {
        return $this->date_naissance;
    }

    /**
     * @param mixed $date_naissance
     */
    public function setDateNaissance($date_naissance): void
    {
        $this->date_naissance = $date_naissance;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getCplAdresse()
    {
        return $this->cpl_adresse;
    }

    /**
     * @return mixed
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * @param mixed $pays
     */
    public function setPays($pays): void
    {
        $this->pays = $pays;
    }


    /**
     * @param mixed $cpl_adresse
     */
    public function setCplAdresse($cpl_adresse): void
    {
        $this->cpl_adresse = $cpl_adresse;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     */
    public function setVille($ville): void
    {
        $this->ville = $ville;
    }

    /**
     * @return mixed
     */
    public function getCodePostal()
    {
        return $this->code_postal;
    }

    /**
     * @param mixed $code_postal
     */
    public function setCodePostal($code_postal): void
    {
        $this->code_postal = $code_postal;
    }

    /**
     * @return mixed
     */
    public function getTelFixe()
    {
        return $this->tel_fixe;
    }

    /**
     * @param mixed $tel_fixe
     */
    public function setTelFixe($tel_fixe): void
    {
        $this->tel_fixe = $tel_fixe;
    }

    /**
     * @return mixed
     */
    public function getTelMobile()
    {
        return $this->tel_mobile;
    }

    /**
     * @param mixed $tel_mobile
     */
    public function setTelMobile($tel_mobile): void
    {
        $this->tel_mobile = $tel_mobile;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

}
