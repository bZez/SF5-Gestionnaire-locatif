<?php

namespace App\Controller\Gestionnaire;

use App\Entity\Logement;
use App\Form\LogementType;
use App\Repository\LogementRepository;
use App\Repository\ResidenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @Route("/logement")
 */
class LogementController extends AbstractController
{
    /**
     * @Route("/{resid}",defaults={"resid"="De la Maine"}, name="logement_index", methods={"GET"})
     */
    public function index(ResidenceRepository $residenceRepository,$resid): Response
    {
        //TODO: CREATION UTILITAIRE ENVOI DPE, RNT
        $residence = $residenceRepository->findOneBy(['nom'=>$resid]);

        return $this->render('gestionnaire/logement/index.html.twig', [
            'residences' =>$residenceRepository->findAll(),
            'residenceChoisie' => $residence,

        ]);
    }

    /**
     * @Route("/{residence}/creation", name="logement_new", methods={"GET","POST"})
     */
    public function new(Request $request,$residence,ResidenceRepository $residenceRepository): Response
    {
//        TODO: CREATION TABLE 'TYPE' AVEC PRIX PAR TYPE (https://www..mgellogement.fr/GEST_V2/residence_tarif_modifier.php?id_residence=30&init=1)
//TODO: CREATION TABLEAU SPECS (https://www..mgellogement.fr/GEST_V2/residence_caracs_logements.php?id_residence=30) PAR LOGEMENT
//TODO: GEESTION CLE FACULTATIVE
        $logement = new Logement();
        $form = $this->createForm(LogementType::class, $logement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if($request->request->get('logementNbr') != null)
            {
                $nbr = $request->request->get('logementNbr');
                for ($i=0;$i<$nbr;$i++)
                {
                    $logement = new Logement();
                    $log = $request->request->get('logement');
                    $logement->setResidence($residenceRepository->findOneBy(['nom'=>$residence]));
                    $logement->setNumero($log['numero']+$i);
                    $logement->setSituation($log['situation']);
                    $logement->setTypeLit($log['type_lit']);
                    $logement->setTypeLogement($log['type_logement']);
                    $logement->setEtage($log['etage']);
                    $logement->setBatiment($log['batiment']);
                    $logement->setCategorie($log['categorie']);
                    $logement->setSurface($log['surface']);
                    $logement->setLoyer($log['loyer']);
                    $logement->setCharges($log['charges']);
                    $logement->setCotisAcc($log['cotis_acc']);
                    $logement->setCotisServices($log['cotis_services']);
                    $logement->setTva($log['tva']);
//                    CLES
                    $logement->setCleDispo($log['Clefs']['cle_dispo']);
                    $logement->setRefCle($log['Clefs']['ref_cle']);
                    $logement->setQteCle($log['Clefs']['qte_cle']);
                    $logement->setQteCleBal($log['Clefs']['qte_cle_bal']);
                    $logement->setRefCleBal($log['Clefs']['ref_cle_bal']);
                    $logement->setRefCleBadge($log['Clefs']['ref_cle_badge']);
                    $logement->setCleResidence($log['Clefs']['cle_residence']);
                    $logement->setCleLocalVelo($log['Clefs']['cle_local_velo']);
                    $logement->setCleSalleCommune($log['Clefs']['cle_salle_commune']);
                    $entityManager->persist($logement);
                }
            }else {
                $entityManager->persist($logement);
            }
            $entityManager->flush();

            return $this->redirectToRoute('logement_index');
        }

        return $this->render('gestionnaire/logement/new.html.twig', [
            'logement' => $logement,
            'form' => $form->createView(),
            'residence' => $residenceRepository->findOneBy(['nom'=>$residence])
        ]);
    }

    /**
     * @Route("/{resid}/{id}", name="logement_show", methods={"GET"})
     */
    public function show(Logement $logement): Response
    {
        //        TODO: SI GREEN PARK POSSIBILITE DE MODIF L'ADRESSE DANS LA FICHE LOGEMENT
        //TODO: DANS LA FICHE LOGEMENT AFFICHER LE DERNIER LOC CONNU ET POSSIBILITE DE REAF.
        //TODO: POSSIBILITE MODIF DEPOT GARATIE PAR LOGEMENT
        return $this->render('gestionnaire/logement/show.html.twig', [
            'logement' => $logement,
            "residenceChoisie" => $logement->getResidence()
        ]);
    }

    /**
     * @Route("/{resid}/{id}/edit", name="logement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Logement $logement): Response
    {
        $form = $this->createForm(LogementType::class, $logement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('logement_index', [
                'id' => $logement->getId(),
            ]);
        }

        return $this->render('gestionnaire/logement/edit.html.twig', [
            'logement' => $logement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="logement_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Logement $logement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$logement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($logement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('logement_index');
    }

    /**
     * @Route("/vide/tous/ok")
     */
    public function vide(LogementRepository $logementRepository)
    {
        $em=$this->getDoctrine()->getManager();
        foreach ($logementRepository->findAll() as $logement)
        {
            $logement->setLoyer(null);
            $logement->setCharges(null);
            $logement->setCotisServices(null);
            $logement->setCotisAcc(null);
            $em->persist($logement);
        }
        $em->flush();
    }
}
