<?php

namespace App\Entity;

use App\Repository\LocataireRepository;
use App\Repository\TypeLogementTarifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectManagerAware;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogementRepository")
 */
class Logement implements ObjectManagerAware
{
    public function injectObjectManager(
        ObjectManager $objectManager,
        ClassMetadata $classMetadata
    )
    {
        $this->em = $objectManager;
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $situation;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $type_lit;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $type_logement;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $etage;

    /**
     * @ORM\Column(type="string",length=2,nullable=true)
     */
    private $batiment;

    /**
     * @ORM\Column(type="string",length=7,nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(type="string",length=1,nullable=true)
     */
    private $categorie;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $surface;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $loyer;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $charges;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $cotis_acc;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $cotis_services;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $tva;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $date_blocage;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $motif_blocage;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $bloque_par;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $cle_dispo;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $ref_cle;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $qte_cle;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $qte_cle_bal;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $ref_cle_bal;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $ref_cle_badge;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $cle_residence;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $cle_local_velo;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $cle_salle_commune;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $cle_commentaire;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $date_maj_cle;


    /**
     * @ORM\ManyToMany(targetEntity="User")
     */
    private $maj_cle_par;

    /**
     * @ORM\Column(type="boolean")
     */
    private $occupation = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EDL", mappedBy="logement", orphanRemoval=true)
     */
    private $edlx;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Residence", inversedBy="logements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $residence;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Todo", mappedBy="logement", orphanRemoval=true)
     */
    private $todos;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_libre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contrat", mappedBy="logement")
     */
    private $contrats;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Locataire", mappedBy="logements")
     */
    private $locataires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Voeu", mappedBy="logement", orphanRemoval=true)
     */
    private $voeux;

    public function __construct()
    {
        $this->edlx = new ArrayCollection();
        $this->todos = new ArrayCollection();
        $this->contrats = new ArrayCollection();
        $this->locataires = new ArrayCollection();
        $this->voeux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getSituation()
    {
        return $this->situation;
    }

    /**
     * @param mixed $situation
     */
    public function setSituation($situation): void
    {
        $this->situation = $situation;
    }

    /**
     * @return mixed
     */
    public function getTypeLit()
    {
        return $this->type_lit;
    }

    /**
     * @param mixed $type_lit
     */
    public function setTypeLit($type_lit): void
    {
        $this->type_lit = $type_lit;
    }

    /**
     * @return mixed
     */
    public function getTypeLogement()
    {
        return $this->type_logement;
    }

    /**
     * @param mixed $type_logement
     */
    public function setTypeLogement($type_logement): void
    {
        $this->type_logement = $type_logement;
    }

    /**
     * @return mixed
     */
    public function getEtage()
    {
        return $this->etage;
    }

    /**
     * @param mixed $etage
     */
    public function setEtage($etage): void
    {
        $this->etage = $etage;
    }

    /**
     * @return mixed
     */
    public function getBatiment()
    {
        return $this->batiment;
    }

    /**
     * @param mixed $batiment
     */
    public function setBatiment($batiment): void
    {
        $this->batiment = $batiment;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie): void
    {
        $this->categorie = $categorie;
    }

    /**
     * @return mixed
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * @param mixed $surface
     */
    public function setSurface($surface): void
    {
        $this->surface = $surface;
    }

    /**
     * @return mixed
     */
    public function getLoyer()
    {
        foreach ($this->getResidence()->getTypeLogementTarifs() as $typeLogementTarif) {
            if ($typeLogementTarif->hasCategorie($this->categorie)) {
                return $typeLogementTarif->hasCategorie($this->categorie)->getLoyer();
            } /*else {
               continue;
            }*/
        }
        /*$eman = $this->em->getRepository(TypeLogementTarif::class);
        return $eman->findFor($this->residence,$this->categorie)->getLoyer();*/
    }

    /**
     * @param mixed $loyer
     */
    public function setLoyer($loyer): void
    {
        $this->loyer = $loyer;
    }

    /**
     * @return mixed
     */
    public function getCharges()
    {
        foreach ($this->getResidence()->getTypeLogementTarifs() as $typeLogementTarif) {
            if ($typeLogementTarif->hasCategorie($this->categorie)) {
                return $typeLogementTarif->hasCategorie($this->categorie)->getCharges();
            }
        }
    }

    /**
     * @param mixed $charges
     */
    public function setCharges($charges): void
    {
        $this->charges = $charges;
    }

    /**
     * @return mixed
     */
    public function getCotisAcc()
    {
        foreach ($this->getResidence()->getTypeLogementTarifs() as $typeLogementTarif) {
            if ($typeLogementTarif->hasCategorie($this->categorie)) {
                return $typeLogementTarif->hasCategorie($this->categorie)->getCotisAcc();
            }
        }
    }

    /**
     * @param mixed $cotis_acc
     */
    public function setCotisAcc($cotis_acc): void
    {
        $this->cotis_acc = $cotis_acc;
    }

    /**
     * @return mixed
     */
    public function getCotisServices()
    {
        foreach ($this->getResidence()->getTypeLogementTarifs() as $typeLogementTarif) {
            if ($typeLogementTarif->hasCategorie($this->categorie)) {
                return $typeLogementTarif->hasCategorie($this->categorie)->getCotisServices();
            }
        }
    }

    /**
     * @param mixed $cotis_services
     */
    public function setCotisServices($cotis_services): void
    {
        $this->cotis_services = $cotis_services;
    }

    /**
     * @return mixed
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * @param mixed $tva
     */
    public function setTva($tva): void
    {
        $this->tva = $tva;
    }

    /**
     * @return mixed
     */
    public function getDateBlocage()
    {
        return $this->date_blocage;
    }

    /**
     * @param mixed $date_blocage
     */
    public function setDateBlocage($date_blocage): void
    {
        $this->date_blocage = $date_blocage;
    }

    /**
     * @return mixed
     */
    public function getMotifBlocage()
    {
        return $this->motif_blocage;
    }

    /**
     * @param mixed $motif_blocage
     */
    public function setMotifBlocage($motif_blocage): void
    {
        $this->motif_blocage = $motif_blocage;
    }

    /**
     * @return mixed
     */
    public function getBloquePar()
    {
        return $this->bloque_par;
    }

    /**
     * @param mixed $bloque_par
     */
    public function setBloquePar($bloque_par): void
    {
        $this->bloque_par = $bloque_par;
    }

    /**
     * @return mixed
     */
    public function getCleDispo()
    {
        return $this->cle_dispo;
    }

    /**
     * @param mixed $cle_dispo
     */
    public function setCleDispo($cle_dispo): void
    {
        $this->cle_dispo = $cle_dispo;
    }

    /**
     * @return mixed
     */
    public function getRefCle()
    {
        return $this->ref_cle;
    }

    /**
     * @param mixed $ref_cle
     */
    public function setRefCle($ref_cle): void
    {
        $this->ref_cle = $ref_cle;
    }

    /**
     * @return mixed
     */
    public function getQteCle()
    {
        return $this->qte_cle;
    }

    /**
     * @param mixed $qte_cle
     */
    public function setQteCle($qte_cle): void
    {
        $this->qte_cle = $qte_cle;
    }

    /**
     * @return mixed
     */
    public function getQteCleBal()
    {
        return $this->qte_cle_bal;
    }

    /**
     * @param mixed $qte_cle_bal
     */
    public function setQteCleBal($qte_cle_bal): void
    {
        $this->qte_cle_bal = $qte_cle_bal;
    }

    /**
     * @return mixed
     */
    public function getRefCleBal()
    {
        return $this->ref_cle_bal;
    }

    /**
     * @param mixed $ref_cle_bal
     */
    public function setRefCleBal($ref_cle_bal): void
    {
        $this->ref_cle_bal = $ref_cle_bal;
    }

    /**
     * @return mixed
     */
    public function getRefCleBadge()
    {
        return $this->ref_cle_badge;
    }

    /**
     * @param mixed $ref_cle_badge
     */
    public function setRefCleBadge($ref_cle_badge): void
    {
        $this->ref_cle_badge = $ref_cle_badge;
    }

    /**
     * @return mixed
     */
    public function getCleResidence()
    {
        return $this->cle_residence;
    }

    /**
     * @param mixed $cle_residence
     */
    public function setCleResidence($cle_residence): void
    {
        $this->cle_residence = $cle_residence;
    }

    /**
     * @return mixed
     */
    public function getCleLocalVelo()
    {
        return $this->cle_local_velo;
    }

    /**
     * @param mixed $cle_local_velo
     */
    public function setCleLocalVelo($cle_local_velo): void
    {
        $this->cle_local_velo = $cle_local_velo;
    }

    /**
     * @return mixed
     */
    public function getCleSalleCommune()
    {
        return $this->cle_salle_commune;
    }

    /**
     * @param mixed $cle_salle_commune
     */
    public function setCleSalleCommune($cle_salle_commune): void
    {
        $this->cle_salle_commune = $cle_salle_commune;
    }

    /**
     * @return mixed
     */
    public function getCleCommentaire()
    {
        return $this->cle_commentaire;
    }

    /**
     * @param mixed $cle_commentaire
     */
    public function setCleCommentaire($cle_commentaire): void
    {
        $this->cle_commentaire = $cle_commentaire;
    }

    /**
     * @return mixed
     */
    public function getDateMajCle()
    {
        return $this->date_maj_cle;
    }

    /**
     * @param mixed $date_maj_cle
     */
    public function setDateMajCle($date_maj_cle): void
    {
        $this->date_maj_cle = $date_maj_cle;
    }

    /**
     * @return mixed
     */
    public function getMajClePar()
    {
        return $this->maj_cle_par;
    }

    /**
     * @param mixed $maj_cle_par
     */
    public function setMajClePar($maj_cle_par): void
    {
        $this->maj_cle_par = $maj_cle_par;
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


    public function getLocataire()
    {
        $last = sizeof($this->locataires) - 1;
        return $this->locataires[$last];
    }

    public function hasLocataire()
    {
        if (!$this->locataires->isEmpty()) {
            return true;
        } else {
            return false;
        }
    }


    public function getLoyerTotal()
    {
        return $this->getLoyer() + $this->getCharges() + $this->getCotisAcc() + $this->getCotisServices();
    }

    public function getInfos($loyer = null, $charges = null, $cotisAcc = null, $cotisServices = null)
    {
        if (!$loyer && !$charges && !$cotisAcc && !$cotisServices)
            return 'Appt N° <b>' . $this->numero . '</b> ~ ' . $this->type_logement . ' ~ ' . $this->type_lit . ' ~ ' . $this->surface . 'm² ~ ' . $this->getLoyerTotal() . '€' . '<span id=fullInfos_' . $this->getId() . ' hidden><span class=loyer>' . $this->getLoyer() . '</span><span class=charges> ' . $this->getCharges() . '</span><span class=cotisAcc>' . $this->getCotisAcc() . '</span><span class=cotisServices>' . $this->getCotisServices() . '</span></span>';
    }

    /**
     * @return Collection|EDL[]
     */
    public function getEdlx(): Collection
    {
        return $this->edlx;
    }

    public function addEdlx(EDL $edlx): self
    {
        if (!$this->edlx->contains($edlx)) {
            $this->edlx[] = $edlx;
            $edlx->setLogement($this);
        }

        return $this;
    }

    public function removeEdlx(EDL $edlx): self
    {
        if ($this->edlx->contains($edlx)) {
            $this->edlx->removeElement($edlx);
            // set the owning side to null (unless already changed)
            if ($edlx->getLogement() === $this) {
                $edlx->setLogement(null);
            }
        }

        return $this;
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
            $todo->setLogement($this);
        }

        return $this;
    }

    public function removeTodo(Todo $todo): self
    {
        if ($this->todos->contains($todo)) {
            $this->todos->removeElement($todo);
            // set the owning side to null (unless already changed)
            if ($todo->getLogement() === $this) {
                $todo->setLogement(null);
            }
        }

        return $this;
    }

    public function getDateLibre(): ?\DateTimeInterface
    {
        return $this->date_libre;
    }

    public function setDateLibre(?\DateTimeInterface $date_libre): self
    {
        $this->date_libre = $date_libre;

        return $this;
    }

    public function getContrat()
    {
        $last = sizeof($this->contrats) - 1;
        return $this->contrats[$last];
    }

    /**
     * @return Collection|Contrat[]
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(Contrat $contrat): self
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats[] = $contrat;
            $contrat->setLogement($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): self
    {
        if ($this->contrats->contains($contrat)) {
            $this->contrats->removeElement($contrat);
            // set the owning side to null (unless already changed)
            if ($contrat->getLogement() === $this) {
                $contrat->setLogement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Locataire[]
     */
    public function getLocataires(): Collection
    {
        return $this->locataires;
    }


    public function addLocataire(Locataire $locataire): self
    {
        if (!$this->locataires->contains($locataire)) {
            $this->locataires[] = $locataire;
            $locataire->addLogement($this);
        }

        return $this;
    }

    public function removeLocataire(Locataire $locataire): self
    {
        if ($this->locataires->contains($locataire)) {
            $this->locataires->removeElement($locataire);
            $locataire->removeLogement($this);
        }

        return $this;
    }

    /**
     * @return Collection|Voeu[]
     */
    public function getVoeux(): Collection
    {
        return $this->voeux;
    }

    public function getVoeu()
    {
        $last = sizeof($this->voeux) - 1;
        return $this->voeux[$last];
    }

    public function addVoeux(Voeu $voeux): self
    {
        if (!$this->voeux->contains($voeux)) {
            $this->voeux[] = $voeux;
            $voeux->setLogement($this);
        }

        return $this;
    }

    public function removeVoeux(Voeu $voeux): self
    {
        if ($this->voeux->contains($voeux)) {
            $this->voeux->removeElement($voeux);
            // set the owning side to null (unless already changed)
            if ($voeux->getLogement() === $this) {
                $voeux->setLogement(null);
            }
        }

        return $this;
    }


}
