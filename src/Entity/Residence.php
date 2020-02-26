<?php

namespace App\Entity;

use App\Repository\LogementRepository;
use App\Repository\ResidenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResidenceRepository")
 */
class Residence
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
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer",length=3)
     */
    private $conso_ener;

    /**
     * @ORM\Column(type="integer",length=3)
     */
    private $emi_gaz;

    /**
     * @ORM\Column(type="json")
     */
    private $services;

    /**
     * @ORM\ManyToMany(targetEntity="Photos")
     */
    private $photos;


    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $video;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $couverture;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $garantie;

    /**
     * @ORM\Column(type="float")
     */
    private $lattitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(type="text")
     */
    private $intro;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Parking", mappedBy="residence", orphanRemoval=true)
     */
    private $parkings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Logement", mappedBy="residence", orphanRemoval=true)
     */
    private $logements;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="residences")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Stats", mappedBy="residence", orphanRemoval=true)
     */
    private $stats;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="residences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TypeLogementTarif", mappedBy="residence", orphanRemoval=true)
     */
    private $typeLogementTarifs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CourtSejour", mappedBy="residence", orphanRemoval=true)
     */
    private $courtSejours;


    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->parkings = new ArrayCollection();
        $this->logements = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->stats = new ArrayCollection();
        $this->typeLogementTarifs = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConsoEner()
    {
        return $this->conso_ener;
    }

    /**
     * @param mixed $conso_ener
     */
    public function setConsoEner($conso_ener): void
    {
        $this->conso_ener = $conso_ener;
    }

    /**
     * @return mixed
     */
    public function getEmiGaz()
    {
        return $this->emi_gaz;
    }

    /**
     * @param mixed $emi_gaz
     */
    public function setEmiGaz($emi_gaz): void
    {
        $this->emi_gaz = $emi_gaz;
    }

    /**
     * @return mixed
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * @param mixed $intro
     */
    public function setIntro($intro): void
    {
        $this->intro = $intro;
    }

    /**
     * @return mixed
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param mixed $services
     */
    public function setServices($services): void
    {
        $this->services = $services;
    }


    public function getPhotos(): Collection
    {
        return $this->photos;
    }


    public function addPhoto($photo)
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
        }
    }

    public function removePhoto($photo)
    {
        if ($this->photos->contains($photo)) {
            $this->photos->remove($photo);
        }
    }


    public function getCouverture()
    {
        return $this->couverture;
    }


    public function setCouverture($couverture)
    {
        $this->couverture = $couverture;
    }

    /**
     * @return mixed
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param mixed $video
     */
    public function setVideo($video): void
    {
        $this->video = $video;
    }

    public function getTypeMin()
    {
        $logements = $this->logements;
        $typeMin = [];
        foreach ($logements as $logement)
        {
            array_push($typeMin,$logement->getTypeLogement());
        }
        if(!empty($typeMin))
        {
            return min($typeMin);
        } else {
            return 0;
        }
    }


    public function getTypeMax()
    {
        $logements = $this->logements;
        $typeMax = [];
        foreach ($logements as $logement)
        {
            array_push($typeMax,$logement->getTypeLogement());
        }
        if(!empty($typeMax))
        {
            return max($typeMax);
        } else {
            return 0;
        }
    }

    public function getTypeMinMax() {
        if ($this->getTypeMin() === $this->getTypeMax()) {
            return $this->getTypeMin();
        } else {
            return $this->getTypeMin() . ' - ' . $this->getTypeMax();
        }
    }


    public function getSurfaceMin()
    {
        $logements = $this->logements;
        $surfaceMin = [];
        foreach ($logements as $logement)
        {
            array_push($surfaceMin,$logement->getSurface());
        }

        if(!empty($surfaceMin))
        {
            return min($surfaceMin);
        } else {
            return 0;
        }
    }

    public function getSurfaceMax()
    {
        $logements = $this->logements;
        $surfaceMax = [];
        foreach ($logements as $logement)
        {
            array_push($surfaceMax,$logement->getSurface());
        }
        if(!empty($surfaceMax))
        {
            return max($surfaceMax);
        } else {
            return 0;
        }
    }

    public function getSurfaceMinMax() {
        if ($this->getSurfaceMin() === $this->getSurfaceMax()) {
            return $this->getSurfaceMin();
        } else {
            return $this->getSurfaceMin() . ' - ' . $this->getSurfaceMax();
        }
    }



    /**
     * @param mixed $garantie
     */
    public function setGarantie($garantie): void
    {
        $this->garantie = $garantie;
    }

    public function getGarantie()
    {
        return $this->garantie;
    }

    public function getLoyerMin()
    {
        $logements = $this->logements;
        $loyerMin = [];
        foreach ($logements as $logement)
        {
            array_push($loyerMin,$logement->getLoyer());
        }
        if(!empty($loyerMin))
        {
            return min($loyerMin);
        } else {
            return 0;
        }
    }
    public function getLoyerMax()
    {
        $logements = $this->logements;
        $loyerMax = [];
        foreach ($logements as $logement)
        {
            array_push($loyerMax,$logement->getLoyer());
        }
       if(!empty($loyerMax))
       {
           return max($loyerMax);
       } else {
           return 0;
       }
    }
    public function getLoyerMinMax()
    {
        if ($this->getLoyerMin() === $this->getLoyerMax()) {
            return $this->getLoyerMin();
        } else {
            return $this->getLoyerMin() . ' - ' . $this->getLoyerMax();
        }
    }

    public function getChargesMinMax()
    {
        $logements = $this->logements;
        $charges = [];
        foreach ($logements as $logement)
        {
            array_push($charges,$logement->getCharges());
        }
        if(!empty($charges))
        {
            if( max($charges) === min($charges))
            {
                return  max($charges);
            } else {
                return min($charges) . ' - ' . max($charges);
            }
        } else {
            return 0;
        }
    }

        public function setLogements($logements)
    {
        $this->logements = $logements;
    }

    /**
     * @return mixed
     */
    public function getLattitude()
    {
        return $this->lattitude;
    }

    /**
     * @param mixed $lattitude
     */
    public function setLattitude($lattitude): void
    {
        $this->lattitude = $lattitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return Collection|Parking[]
     */
    public function getParkings(): Collection
    {
        return $this->parkings;
    }

    public function addParking(Parking $parking): self
    {
        if (!$this->parkings->contains($parking)) {
            $this->parkings[] = $parking;
            $parking->setResidence($this);
        }

        return $this;
    }

    public function removeParking(Parking $parking): self
    {
        if ($this->parkings->contains($parking)) {
            $this->parkings->removeElement($parking);
            // set the owning side to null (unless already changed)
            if ($parking->getResidence() === $this) {
                $parking->setResidence(null);
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

    public function addLogement(Logement $logement): self
    {
        if (!$this->logements->contains($logement)) {
            $this->logements[] = $logement;
            $logement->setResidence($this);
        }

        return $this;
    }

    public function removeLogement(Logement $logement): self
    {
        if ($this->logements->contains($logement)) {
            $this->logements->removeElement($logement);
            // set the owning side to null (unless already changed)
            if ($logement->getResidence() === $this) {
                $logement->setResidence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addResidence($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeResidence($this);
        }

        return $this;
    }

    /**
     * @return Collection|Stats[]
     */
    public function getStats(): Collection
    {
        return $this->stats;
    }

    public function addStat(Stats $stat): self
    {
        if (!$this->stats->contains($stat)) {
            $this->stats[] = $stat;
            $stat->setResidence($this);
        }

        return $this;
    }

    public function removeStat(Stats $stat): self
    {
        if ($this->stats->contains($stat)) {
            $this->stats->removeElement($stat);
            // set the owning side to null (unless already changed)
            if ($stat->getResidence() === $this) {
                $stat->setResidence(null);
            }
        }

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|TypeLogementTarif[]
     */
    public function getTypeLogementTarifs(): Collection
    {
        return $this->typeLogementTarifs;
    }

    public function addTypeLogementTarif(TypeLogementTarif $typeLogementTarif): self
    {
        if (!$this->typeLogementTarifs->contains($typeLogementTarif)) {
            $this->typeLogementTarifs[] = $typeLogementTarif;
            $typeLogementTarif->setResidence($this);
        }

        return $this;
    }

    public function removeTypeLogementTarif(TypeLogementTarif $typeLogementTarif): self
    {
        if ($this->typeLogementTarifs->contains($typeLogementTarif)) {
            $this->typeLogementTarifs->removeElement($typeLogementTarif);
            // set the owning side to null (unless already changed)
            if ($typeLogementTarif->getResidence() === $this) {
                $typeLogementTarif->setResidence(null);
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
            $courtSejour->setResidence($this);
        }

        return $this;
    }

    public function removeCourtSejour(CourtSejour $courtSejour): self
    {
        if ($this->courtSejours->contains($courtSejour)) {
            $this->courtSejours->removeElement($courtSejour);
            // set the owning side to null (unless already changed)
            if ($courtSejour->getResidence() === $this) {
                $courtSejour->setResidence(null);
            }
        }

        return $this;
    }


}
