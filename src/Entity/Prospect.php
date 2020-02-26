<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProspectRepository")
 */
class Prospect implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $civilite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;
    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $date_naissance;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=3,nullable=true)
     */
    private $civilite_garant;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $nom_garant;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $prenom_garant;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $date_naissance_garant;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $telephone_garant;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $email_garant;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $adresse_garant;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $ville_garant;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $code_postal_garant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $promo_mgel = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $promo_partenaire = 0;

    /**
     * @ORM\ManyToMany(targetEntity="Residence")
     */
    private $residences;

    /**
     * @ORM\Column(type="date")
     */
    private $date_entree;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $date_sortie;

    /**
     * @ORM\Column(type="date")
     */
    private $date_demande;

    /**
     * @ORM\Column(type="boolean")
     */
    private $prioritaire;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $colocation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etranger;

    /**
     * @ORM\Column(type="string")
     */
    private $statut;

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
     * @ORM\Column(type="string",nullable=true)
     */
    private $cni_garant;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $livret;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $justif_dom;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $bull_sal;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $avis_imp;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $date_accep;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $accept_by;

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
     * @ORM\Column(type="json")
     */
    private $roles = [];

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
    private $bullSalLoc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avisImpLoc;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_recep_dossier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rib;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sepa;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_refus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="prospects")
     */
    private $refuse_par;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $motif_refus;

    public function __construct()
    {
        $this->date_demande = new \DateTime('now');
        $this->residences = new ArrayCollection();
        $this->statut = 'EN ATTENTE';
        $this->cheque_frais = false;
        $this->prioritaire = false;
        $this->etranger = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
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
        $roles[] = 'ROLE_PROSPECT';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
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

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

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

    public function getPromoMgel()
    {
        return $this->promo_mgel;
    }

    public function setPromoMgel($promo_mgel)
    {
        $this->promo_mgel = $promo_mgel;

        return $this;
    }

    public function getPromoPartenaire()
    {
        return $this->promo_partenaire;
    }

    public function setPromoPartenaire($promo_partenaire)
    {
        $this->promo_partenaire = $promo_partenaire;

        return $this;
    }

    public function getResidences()
    {
        return $this->residences->toArray();
    }

    public function addResidence($residence)
    {
        if (!$this->residences->contains($residence)) {
            $this->residences->add($residence);
        }

    }

    public function removeResidence($residence)
    {
        if ($this->residences->contains($residence)) {
            $this->residences->remove($residence);
        }

    }

    public function getDateEntree()
    {
        return $this->date_entree;
    }

    public function setDateEntree($date_entree)
    {
        $this->date_entree = $date_entree;

        return $this;
    }

    public function getDateSortie()
    {
        return $this->date_sortie;
    }

    public function setDateSortie($date_sortie)
    {
        $this->date_sortie = $date_sortie;

        return $this;
    }

    public function getDateDemande()
    {
        return $this->date_demande;
    }

    public function setDateDemande($date_demande)
    {
        $this->date_demande = $date_demande;
    }

    /**
     * @return mixed
     */
    public function getCiviliteGarant()
    {
        return $this->civilite_garant;
    }

    /**
     * @param mixed $civilite_garant
     */
    public function setCiviliteGarant($civilite_garant): void
    {
        $this->civilite_garant = $civilite_garant;
    }

    /**
     * @return mixed
     */
    public function getNomGarant()
    {
        return $this->nom_garant;
    }

    /**
     * @param mixed $nom_garant
     */
    public function setNomGarant($nom_garant): void
    {
        $this->nom_garant = $nom_garant;
    }

    /**
     * @return mixed
     */
    public function getPrenomGarant()
    {
        return $this->prenom_garant;
    }

    /**
     * @param mixed $prenom_garant
     */
    public function setPrenomGarant($prenom_garant): void
    {
        $this->prenom_garant = $prenom_garant;
    }

    /**
     * @return mixed
     */
    public function getTelephoneGarant()
    {
        return $this->telephone_garant;
    }

    /**
     * @param mixed $telephone_garant
     */
    public function setTelephoneGarant($telephone_garant): void
    {
        $this->telephone_garant = $telephone_garant;
    }

    /**
     * @return mixed
     */
    public function getEmailGarant()
    {
        return $this->email_garant;
    }

    /**
     * @param mixed $email_garant
     */
    public function setEmailGarant($email_garant): void
    {
        $this->email_garant = $email_garant;
    }

    /**
     * @return mixed
     */
    public function getAdresseGarant()
    {
        return $this->adresse_garant;
    }

    /**
     * @param mixed $adresse_garant
     */
    public function setAdresseGarant($adresse_garant): void
    {
        $this->adresse_garant = $adresse_garant;
    }

    /**
     * @return mixed
     */
    public function getVilleGarant()
    {
        return $this->ville_garant;
    }

    /**
     * @param mixed $ville_garant
     */
    public function setVilleGarant($ville_garant): void
    {
        $this->ville_garant = $ville_garant;
    }

    /**
     * @return mixed
     */
    public function getCodePostalGarant()
    {
        return $this->code_postal_garant;
    }

    /**
     * @param mixed $code_postal_garant
     */
    public function setCodePostalGarant($code_postal_garant): void
    {
        $this->code_postal_garant = $code_postal_garant;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut): void
    {
        $this->statut = $statut;
    }

    /**
     * @return mixed
     */
    public function getPrioritaire()
    {
        return $this->prioritaire;
    }


    public function setPrioritaire($prioritaire = null)
    {
        if ($prioritaire) {
            $this->prioritaire = $prioritaire;
        } else {
            if ($this->prioritaire == '1') {
                $this->prioritaire = 0;
            } else {
                $this->prioritaire = 1;
            }
        }

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

    public function setChequeFrais($cheque = null): void
    {
        if ($cheque) {
            $this->cheque_frais = $cheque;
        } else {
            if ($this->cheque_frais == true) {
                $this->cheque_frais = false;
            } else {
                $this->cheque_frais = true;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getCniGarant()
    {
        return $this->cni_garant;
    }

    /**
     * @param mixed $cni_garant
     */
    public function setCniGarant($cni_garant): void
    {
        $this->cni_garant = $cni_garant;
    }

    /**
     * @return mixed
     */
    public function getLivret()
    {
        return $this->livret;
    }

    /**
     * @param mixed $livret
     */
    public function setLivret($livret): void
    {
        $this->livret = $livret;
    }

    /**
     * @return mixed
     */
    public function getJustifDom()
    {
        return $this->justif_dom;
    }

    /**
     * @param mixed $justif_dom
     */
    public function setJustifDom($justif_dom): void
    {
        $this->justif_dom = $justif_dom;
    }

    /**
     * @return mixed
     */
    public function getBullSal()
    {
        return $this->bull_sal;
    }

    /**
     * @param mixed $bull_sal
     */
    public function setBullSal($bull_sal): void
    {
        $this->bull_sal = $bull_sal;
    }

    /**
     * @return mixed
     */
    public function getAvisImp()
    {
        return $this->avis_imp;
    }

    /**
     * @param mixed $avis_imp
     */
    public function setAvisImp($avis_imp): void
    {
        $this->avis_imp = $avis_imp;
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

    /**
     * @return mixed
     */
    public function getColocation()
    {
        return $this->colocation;
    }

    public function setColocation($colocation = null)
    {
        if ($colocation) {
            $this->colocation = $colocation;
        } else {
            if ($this->colocation == '1') {
                $this->colocation = 0;
            } else {
                $this->colocation = 1;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getEtranger()
    {
        return $this->etranger;
    }


    public function setEtranger($etranger = null)
    {
        if ($etranger) {
            $this->etranger = $etranger;
        } else {
            if ($this->etranger == '1') {
                $this->etranger = 0;
            } else {
                $this->etranger = 1;
            }
        }
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
    public function getDateNaissanceGarant()
    {
        return $this->date_naissance_garant;
    }

    /**
     * @param mixed $date_naissance_garant
     */
    public function setDateNaissanceGarant($date_naissance_garant): void
    {
        $this->date_naissance_garant = $date_naissance_garant;
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

    public function getBullSalLoc(): ?string
    {
        return $this->bullSalLoc;
    }

    public function setBullSalLoc(?string $bullSalLoc): self
    {
        $this->bullSalLoc = $bullSalLoc;

        return $this;
    }

    public function getAvisImpLoc(): ?string
    {
        return $this->avisImpLoc;
    }

    public function setAvisImpLoc(?string $avisImpLoc): self
    {
        $this->avisImpLoc = $avisImpLoc;

        return $this;
    }

    public function getDateRecepDossier(): ?\DateTimeInterface
    {
        return $this->date_recep_dossier;
    }

    public function setDateRecepDossier(?\DateTimeInterface $date_recep_dossier): self
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

    public function getSepa(): ?string
    {
        return $this->sepa;
    }

    public function setSepa(?string $sepa): self
    {
        $this->sepa = $sepa;

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

    public function getDateRefus(): ?\DateTimeInterface
    {
        return $this->date_refus;
    }

    public function setDateRefus(?\DateTimeInterface $date_refus): self
    {
        $this->date_refus = $date_refus;

        return $this;
    }

    public function getRefusePar(): ?User
    {
        return $this->refuse_par;
    }

    public function setRefusePar(?User $refuse_par): self
    {
        $this->refuse_par = $refuse_par;

        return $this;
    }

    public function getMotifRefus(): ?string
    {
        return $this->motif_refus;
    }

    public function setMotifRefus(?string $motif_refus): self
    {
        $this->motif_refus = $motif_refus;

        return $this;
    }

    public function isValid()
    {
        if ($this->getBullSal() &&
            $this->getAvisImp() &&
            $this->getCniGarant() &&
            $this->getLivret() &&
            $this->getJustifDom() &&
            $this->getPhoto() &&
            $this->getSepa() &&
            $this->getRib() &&
            $this->getCni() &&
            $this->getChequeFrais()) {
            switch ($this->statut_pro) {
                case true:
                    if ($this->getBullSalLoc() &&
                        $this->getAvisImpLoc() &&
                        $this->getContratTrav())
                    {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case false:
                    if ($this->getJustifScol())
                    {
                        return true;
                    } else {
                        return false;
                    }
                    break;
            }
        }
        return false;
    }

}
