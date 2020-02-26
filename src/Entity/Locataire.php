<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocataireRepository")
 */
class Locataire implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20,nullable=true)
     */
    private $type_adh;

    /**
     * @ORM\Column(type="string", length=20,nullable=true)
     */
    private $type_mrh;

    /**
     * @ORM\Column(type="string", length=5,nullable=true)
     */
    private $civilite;


    /**
     * @ORM\OneToOne(targetEntity="Garant")
     */
    private $garant;

    /**
     * @ORM\OneToOne(targetEntity="Tuteur")
     */
    private $parent;

    /**
     * @ORM\Column(type="integer", length=10,nullable=true)
     */
    private $dossier;

    /**
     * @ORM\Column(type="string", length=15,nullable=true)
     */
    private $code_insee;


    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $externe;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $cnil_mgel;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $cnil_partenaires;

    /**
     * @ORM\Column(type="string", length=180,nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=180,nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="string", length=50,nullable=true)
     */
    private $ville_naissance;

    /**
     * @ORM\Column(type="string", length=180,nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=180,nullable=true)
     */
    private $cpl_adresse;

    /**
     * @ORM\Column(type="string", length=50,nullable=true)
     */
    private $ville;

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
     * @ORM\Column(type="text",nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=180,nullable=true,unique=true)
     */
    private $email;


    /**
     * @ORM\OneToOne(targetEntity="Voeu")
     */
    private $voeu;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $date_record;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $record_by;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $prioritaire = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $colocation = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etranger = 0;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $cni;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $justif_bourse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cheque_frais;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statut_pro;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $justifScol;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contratTrav;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bullSal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avisImp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rib;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string",nullable=true)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EDL", mappedBy="locataire", orphanRemoval=true)
     */
    private $edlx;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Parking", mappedBy="locataire", cascade={"persist", "remove"})
     */
    private $parking;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contrat", mappedBy="locataire")
     */
    private $contrats;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Todo", mappedBy="locataire", orphanRemoval=true)
     */
    private $todos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Logement", inversedBy="locataires")
     */
    private $logements;

    /**
     * @ORM\Column(type="date")
     */
    private $date_recep_dossier;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $date_accep;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $accept_by;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Sepa", mappedBy="locataire", cascade={"persist", "remove"})
     */
    private $sepa;

    public function __construct()
    {
        $this->date_record = new \DateTime('now');
        $this->contrats = new ArrayCollection();
        $this->todos = new ArrayCollection();
        $this->logements = new ArrayCollection();
        $this->statut = 'VALIDE';
    }

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


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_LOCATAIRE
        $roles[] = 'ROLE_LOCATAIRE';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getTypeAdh()
    {
        return $this->type_adh;
    }

    /**
     * @param mixed $id_adh
     */
    public function setTypeAdh($type_adh): void
    {
        $this->type_adh = $type_adh;
    }

    /**
     * @return mixed
     */
    public function getTypeMrh()
    {
        return $this->type_mrh;
    }

    /**
     * @param mixed $id_mrh
     */
    public function setTypeMrh($type_mrh): void
    {
        $this->type_mrh = $type_mrh;
    }

    /**
     * @return mixed
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * @return mixed
     */
    public function getCnilMgel()
    {
        return $this->cnil_mgel;
    }

    /**
     * @param mixed $cnil_mgel
     */
    public function setCnilMgel($cnil_mgel): void
    {
        $this->cnil_mgel = $cnil_mgel;
    }

    /**
     * @return mixed
     */
    public function getCnilPartenaires()
    {
        return $this->cnil_partenaires;
    }

    /**
     * @return \DateTime
     */
    public function getDateRecord()
    {
        return $this->date_record;
    }

    /**
     * @param mixed $date_record
     */
    public function setDateRecord($date_record): void
    {
        $this->date_record = $date_record;
    }

    /**
     * @param mixed $cnil_partenaires
     */
    public function setCnilPartenaires($cnil_partenaires): void
    {
        $this->cnil_partenaires = $cnil_partenaires;
    }


    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;
    }



    /**
     * @return mixed
     */
    public function getGarant()
    {
        return $this->garant;
    }

    /**
     * @param mixed $garant
     */
    public function setGarant($garant): void
    {
        $this->garant = $garant;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * @param mixed $dossier
     */
    public function setDossier($dossier): void
    {
        $this->dossier = $dossier;
    }

    /**
     * @return mixed
     */
    public function getCodeInsee()
    {
        return $this->code_insee;
    }

    /**
     * @param mixed $code_insee
     */
    public function setCodeInsee($code_insee): void
    {
        $this->code_insee = $code_insee;
    }


    /**
     * @return mixed
     */
    public function getExterne()
    {
        return $this->externe;
    }

    /**
     * @param mixed $externe
     */
    public function setExterne($externe): void
    {
        $this->externe = $externe;
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
    public function getVilleNaissance()
    {
        return $this->ville_naissance;
    }

    /**
     * @param mixed $ville_naissance
     */
    public function setVilleNaissance($ville_naissance): void
    {
        $this->ville_naissance = $ville_naissance;
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
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param mixed $commentaire
     */
    public function setCommentaire($commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return mixed
     */
    public function getVoeu()
    {
        return $this->voeu;
    }

    /**
     * @param mixed $voeu
     */
    public function setVoeu($voeu): void
    {
        $this->voeu = $voeu;
    }

    /**
     * @return mixed
     */
    public function getRecordBy()
    {
        return $this->record_by;
    }

    /**
     * @param mixed $record_by
     */
    public function setRecordBy($record_by): void
    {
        $this->record_by = $record_by;
    }

    /**
     * @return mixed
     */
    public function getPrioritaire()
    {
        return $this->prioritaire;
    }

    /**
     * @param mixed $prioritaire
     */
    public function setPrioritaire($prioritaire): void
    {
        $this->prioritaire = $prioritaire;
    }

    /**
     * @return mixed
     */
    public function getColocation()
    {
        return $this->colocation;
    }

    /**
     * @param mixed $colocation
     */
    public function setColocation($colocation): void
    {
        $this->colocation = $colocation;
    }

    /**
     * @return mixed
     */
    public function getEtranger()
    {
        return $this->etranger;
    }

    /**
     * @param mixed $etranger
     */
    public function setEtranger($etranger): void
    {
        $this->etranger = $etranger;
    }

    /**
     * @return mixed
     */
    public function getCni()
    {
        return $this->cni;
    }

    /**
     * @param mixed $cni
     */
    public function setCni($cni): void
    {
        $this->cni = $cni;
    }

    /**
     * @return mixed
     */
    public function getJustifBourse()
    {
        return $this->justif_bourse;
    }

    /**
     * @param mixed $justif_bourse
     */
    public function setJustifBourse($justif_bourse): void
    {
        $this->justif_bourse = $justif_bourse;
    }

    /**
     * @return mixed
     */
    public function getChequeFrais()
    {
        return $this->cheque_frais;
    }

    /**
     * @param mixed $cheque_frais
     */
    public function setChequeFrais($cheque_frais): void
    {
        $this->cheque_frais = $cheque_frais;
    }

    public function getNomComplet()
    {
        return mb_strtoupper($this->nom) . ' ' . $this->prenom;
    }

    /**
     * @return mixed
     */
    public function getEdlx()
    {
        return $this->edlx;
    }

    /**
     * @param mixed $edlx
     */
    public function setEdlx($edlx): void
    {
        $this->edlx = $edlx;
    }

    public function getEdle()
    {
        foreach ($this->edlx as $edl)
        {
            if($edl->getType() === 'EDLE')
            {
                return $edl;
            }
        };
    }
    public function getEdls()
    {
        foreach ($this->edlx as $edl)
        {
            if($edl->getType() === 'EDLS')
            {
                return $edl;
            }
        };
    }

    public function getParking(): ?Parking
    {
        return $this->parking;
    }

    public function setParking(?Parking $parking): self
    {
        $this->parking = $parking;

        // set (or unset) the owning side of the relation if necessary
        $newLocataire = $parking === null ? null : $this;
        if ($newLocataire !== $parking->getLocataire()) {
            $parking->setLocataire($newLocataire);
        }

        return $this;
    }

    /**
     * @return Collection|Contrat[]
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    /**
     * @return Contrat
     */
    public function getLastContrat()
    {
        $last = sizeof($this->contrats)-1;
        return $this->contrats[$last];
    }

    public function addContrat(Contrat $contrat): self
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats[] = $contrat;
            $contrat->setLocataire($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): self
    {
        if ($this->contrats->contains($contrat)) {
            $this->contrats->removeElement($contrat);
            // set the owning side to null (unless already changed)
            if ($contrat->getLocataire() === $this) {
                $contrat->setLocataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Todo[]
     */
    public function getTodos(): Collection
    {
        return $this->todos;
    }

    public function addTodo(Todo $todo): self
    {
        if (!$this->todos->contains($todo)) {
            $this->todos[] = $todo;
            $todo->setLocataire($this);
        }

        return $this;
    }

    public function removeTodo(Todo $todo): self
    {
        if ($this->todos->contains($todo)) {
            $this->todos->removeElement($todo);
            // set the owning side to null (unless already changed)
            if ($todo->getLocataire() === $this) {
                $todo->setLocataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Logement[]
     */
    public function getLogements(): Collection
    {
        return $this->logements;
    }

    public function getLogement()
    {
        $last = sizeof($this->logements)-1;
        return $this->logements[$last];
    }

    public function getResidence()
    {
        return $this->getLogement()->getResidence();
    }

    public function hasResidence(Residence $residence)
    {
       if($this->getResidence() === $residence){
           return true;
       } else {
           return false;
       }
    }

    public function addLogement(Logement $logement): self
    {
        if (!$this->logements->contains($logement)) {
            $this->logements[] = $logement;
        }

        return $this;
    }

    public function removeLogement(Logement $logement): self
    {
        if ($this->logements->contains($logement)) {
            $this->logements->removeElement($logement);
        }

        return $this;
    }
    public function getStatutPro(): ?bool
    {
        return $this->statut_pro;
    }

    public function setStatutPro(?bool $statut_pro): self
    {
        $this->statut_pro = $statut_pro;

        return $this;
    }

    public function getJustifScol(): ?string
    {
        return $this->justifScol;
    }

    public function setJustifScol(?string $justifScol): self
    {
        $this->justifScol = $justifScol;

        return $this;
    }

    public function getContratTrav(): ?string
    {
        return $this->contratTrav;
    }

    public function setContratTrav(?string $contratTrav): self
    {
        $this->contratTrav = $contratTrav;

        return $this;
    }

    public function getBullSal(): ?string
    {
        return $this->bullSal;
    }

    public function setBullSal(?string $bullSal): self
    {
        $this->bullSal = $bullSal;

        return $this;
    }

    public function getAvisImp(): ?string
    {
        return $this->avisImp;
    }

    public function setAvisImp(?string $avisImp): self
    {
        $this->avisImp = $avisImp;

        return $this;
    }

    public function getDateRecepDossier(): ?\DateTimeInterface
    {
        return $this->date_recep_dossier;
    }

    public function setDateRecepDossier(\DateTimeInterface $date_recep_dossier): self
    {
        $this->date_recep_dossier = $date_recep_dossier;

        return $this;
    }
    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(?string $rib): self
    {
        $this->rib = $rib;

        return $this;
    }


    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getDateAccep()
    {
        return $this->date_accep;
    }

    /**
     * @param mixed $date_accep
     */
    public function setDateAccep($date_accep): void
    {
        $this->date_accep = $date_accep;
    }

    /**
     * @return mixed
     */
    public function getAcceptBy()
    {
        return $this->accept_by;
    }

    /**
     * @param mixed $accept_by
     */
    public function setAcceptBy($accept_by): void
    {
        $this->accept_by = $accept_by;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
    public function isValid()
    {
        return true;
    }

    public function getSepa(): ?Sepa
    {
        return $this->sepa;
    }

    public function setSepa(Sepa $sepa): self
    {
        $this->sepa = $sepa;

        // set the owning side of the relation if necessary
        if ($this !== $sepa->getLocataire()) {
            $sepa->setLocataire($this);
        }

        return $this;
    }
}
