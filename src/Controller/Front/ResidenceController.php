<?php

namespace App\Controller\Front;

use App\Entity\Disponibilite;
use App\Repository\DisponibiliteRepository;
use App\Repository\LocataireRepository;
use App\Repository\LogementRepository;
use App\Repository\ResidenceRepository;
use App\Repository\VilleRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/",host="51.83.98.191")
 */
class ResidenceController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(ResidenceRepository $residenceRepository, VilleRepository $villeRepository, Request $request)
    {
        $villes = $villeRepository->findAll();
        $residence = array();
        foreach ($villes as $ville) {
            $residence[$ville->getNom()] = $residenceRepository->findBy(['ville' => $ville]);
        }
        if ($request->isMethod('POST')){
            $residences = $residenceRepository->findBy(['ville' => $_POST['ville']]);
            return $this->render('front/residence/liste.html.twig', [
                'villes' => $villes,
                'residences' => $residences,
            ]);
        }
        return $this->render('front/residence/liste.html.twig', [
            'villes' => $villes,
            'res' =>$residenceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/residences/{ville}", name="liste_residences")
     */
    public function liste(ResidenceRepository $residenceRepository, VilleRepository $villeRepository, $ville,LogementRepository $logementRepository)
    {
        $ville = $villeRepository->findOneBy(['nom' => $ville]);
        $residence[$ville->getNom()] = $residenceRepository->findBy(['ville' => $ville]);
        /*$loyerMin = $logementRepository->findOneBy(['residence'=>$residence],['loyer'=>'ASC'])->getLoyer();
        $loyerMax = $logementRepository->findOneBy(['residence'=>$residence],['loyer'=>'DESC'])->getLoyer();
        $typeMin = $logementRepository->findOneBy(['residence'=>$residence],['type_logement'=>'ASC'])->getTypeLogement();
        $typeMax = $logementRepository->findOneBy(['residence'=>$residence],['type_logement'=>'DESC'])->getTypeLogement();
        $surfaceMin = $logementRepository->findOneBy(['residence'=>$residence],['surface'=>'ASC'])->getSurface();
        $surfaceMax = $logementRepository->findOneBy(['residence'=>$residence],['surface'=>'DESC'])->getSurface();
        $logements = $logementRepository->findByResidence($residence);
        $residence->setLoyerMin($loyerMin);
        $residence->setLoyerMax($loyerMax);
        $residence->setTypeMin($typeMin);
        $residence->setTypeMax($typeMax);
        $residence->setSurfaceMin($surfaceMin);
        $residence->setSurfaceMax($surfaceMax);
        $residence->setLogements($logements);*/
        return $this->render('front/residence/ville.html.twig', [
            'ville' => $ville,
            'residence' => $residence,
        ]);
    }

    /**
     * @Route("/residences/{ville}/{nom}", name="infos_residence")
     */
    public function infos(ResidenceRepository $residenceRepository, LogementRepository $logementRepository,  $ville, $nom)
    {
        $residence = $residenceRepository->findOneBy(['nom' => $nom]);
        /*if ($logementRepository->findOneBy(['residence' => $residence])) {
            $type_min = $logementRepository->findOneBy(['residence' => $residence], ['type' => 'ASC'])->getType();
            $type_max = $logementRepository->findOneBy(['residence' => $residence], ['type' => 'DESC'])->getType();
            $loyer_min = $logementRepository->findOneBy(['residence' => $residence], ['loyer' => 'ASC'])->getLoyer();
            $loyer_max = $logementRepository->findOneBy(['residence' => $residence], ['loyer' => 'DESC'])->getLoyer();
            $surface_min = $logementRepository->findOneBy(['residence' => $residence], ['surface' => 'ASC'])->getSurface();
            $surface_max = $logementRepository->findOneBy(['residence' => $residence], ['surface' => 'DESC'])->getSurface();
            $depot_min = $logementRepository->findOneBy(['residence' => $residence], ['depot' => 'ASC'])->getDepot();
            $depot_max = $logementRepository->findOneBy(['residence' => $residence], ['depot' => 'DESC'])->getDepot();
            $dispos = $logementRepository->findBy(['residence' => $residence, 'disponible' => true]);
            if (count($dispos) > 0) {
                $dispo = true;
            } else {
                $dispo = false;
            }

            $criteria = Criteria::create()->where(Criteria::expr()->eq('residence', $residence))->andWhere(Criteria::expr()->gt('annee', 2018));
            $disponibilites = $this->getDoctrine()->getRepository(Disponibilite::class)->matching($criteria);

            return $this->render('front/residence/residence.html.twig', [
                'residence' => $residence,
                'typeMin' => $type_min,
                'typeMax' => $type_max,
                'loyerMin' => $loyer_min,
                'loyerMax' => $loyer_max,
                'surfaceMin' => $surface_min,
                'surfaceMax' => $surface_max,
                'depotMin' => $depot_min,
                'depotMax' => $depot_max,
                'dispo' => $dispo,
                'disponibilites' => $disponibilites,
            ]);
        } else {*/
            return $this->render('front/residence/residence.html.twig', [
                'residence' => $residence,
                'loaded' => true
            ]);
       /* }*/
    }
}
