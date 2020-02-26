<?php

namespace App\Controller\Gestionnaire;

use App\Entity\Logement;
use App\Repository\LocataireRepository;
use App\Repository\LogementRepository;
use App\Repository\ResidenceRepository;
use App\Repository\StatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/",host="gest.mgellogement.fr")
 */
class GlobalController extends AbstractController
{

    /**
     * @Route("/stats/{resid}", name="stats_residence")
     */
    public function statsResid($resid, ResidenceRepository $residenceRepository,StatsRepository $statsRepository,LogementRepository $logementRepository,LocataireRepository $locataireRepository)
    {
        //TODO: MENU LOCATAIRES - NOUVELLE LOCATION = LISTE APPART LIBRE
        //TODO: DANS LA FICHE LOGEMENT AFFICHER LE DERNIER LOC CONNU ET POSSIBILITE DE REAF.
        //TODO: BLOQUER LE LOGEMENT (SU OU GESTION) OU HOTELIER (LOGEMENT INVISIBLE SAUF SU) - https://www..mgellogement.fr/GEST_V2/logement_detail.php?id_logement=1142 | SI BLOCAGE DEMANDER MOTIF - BLOQUE PAR - ATTRIB IMPOSSIBLE
        $now = new \DateTime('now');
        $residence = $residenceRepository->findOneBy(['nom' => $resid]);
        $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_ANIM']);
        $locCount = 0;
        $locCountYear = 0;
        $locCountMonth = 0;
        $locCountDay = 0;
        $nbrLog = count($logementRepository->findByResidence($residence));
        $nbrLoc = count($locataireRepository->findByResidence($residence));
        $taux = ($nbrLoc/$nbrLog)*100;
        foreach ($locataireRepository->findByResidence($residence) as $locataire)
        {
                $locCount++;
                if($locataire->getDateRecord()->format('y') == $now->format('y'))
                {
                    $locCountYear++;
                }
                if($locataire->getDateRecord()->format('m') == $now->format('m'))
                {
                    $locCountMonth++;
                }
                if($locataire->getDateRecord()->format('d') == $now->format('d'))
                {
                    $locCountDay++;
                }
            $tauxYear =($locCountYear/$nbrLog)*100;
            $tauxMonth =($locCountMonth/$nbrLog)*100;
        }

        return $this->render('gestionnaire/stats/index.html.twig', [
            'residence' =>$residence,
            'newProspectAll' => $statsRepository->getForEverytime('Prospect',$residence),
            'newProspectYear' => $statsRepository->getForYear('Prospect', $now->format('Y'),$residence),
            'newProspectMonth' => $statsRepository->getForMonth('Prospect', $now->format('m'),$residence),
            'newProspectDay' => $statsRepository->getForDay('Prospect', $now->format('d'),$residence),
            'countLocataireAll' => $locCount,
            'countLocataireYear' => $locCountYear,
            'countLocataireMonth' => $locCountMonth,
            'countLocataireDay' => $locCountDay,
            'tauxOccupationAll' => $taux,
            'tauxOccupationYear' =>$tauxYear,
            'tauxOccupationMonth' =>$tauxMonth,
        ]);
    }


    /**
     * @Route("/stats", name="stats")
     */
    public function stats(StatsRepository $statsRepository,LocataireRepository $locataireRepository,LogementRepository $logementRepository,ResidenceRepository $residenceRepository)
    {
        $nbrLog = count($logementRepository->findAll());
        $nbrLoc = count($locataireRepository->findAll());
        $taux = ($nbrLoc/$nbrLog)*100;
        $now = new \DateTime('now');
        $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_ANIM']);
        return $this->render('gestionnaire/stats/index.html.twig', [
            'newProspectAll' => $statsRepository->getForEverytime('Prospect'),
            'newProspectYear' => $statsRepository->getForYear('Prospect', $now->format('Y')),
            'newProspectMonth' => $statsRepository->getForMonth('Prospect', $now->format('m')),
            'newProspectDay' => $statsRepository->getForDay('Prospect', $now->format('d')),
            'countLocataireAll' => $locataireRepository->getCountAll(),
            'countLocataireYear' => $locataireRepository->getCountForYear($now->format('Y')),
            'countLocataireMonth' => $locataireRepository->getCountForMonth($now->format('m')),
            'countLocataireDay' => $locataireRepository->getCountForDay($now->format('d')),
            'tauxOccupationAll' => $taux,
            'tauxOccupationYear' =>($locataireRepository->getCountForYear($now->format('Y'))/$nbrLog)*100,
            'tauxOccupationMonth' =>($locataireRepository->getCountForMonth($now->format('m'))/$nbrLog)*100,
        ]);
    }

    /**
     * @Route("/log")
     */
    public function test2(ResidenceRepository $residenceRepository)
    {
        $em = $this->getDoctrine()->getManager('old_gest');
        $em2 = $this->getDoctrine()->getManager();
        $req = "SELECT * FROM dbo.LOGEMENT";
        // $req = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'LOCATAIRE'";
        $statement = $em->getConnection()->prepare($req);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach ($results as $result) {
            $logement = new Logement();
            $id_res = $result['ID_RESIDENCE'];
            $residence = $residenceRepository->find($id_res);
            $situation = $result['ID_SITUATION'];
            $type_lit = $result['ID_TYPLIT'];
            $etage = $result['ID_ETAGE'];
            $batiment = $result['BATIMENT'];
            $numero = $result['NUM_APPT'];
            $categorie = $result['CATEGORIE'];
            $type_logement = $result['ID_TYPOLOGIE'];
            $surface = $result['SURFACE_REELLE'];
            $loyer = $result['LOYER'];
            $charges = $result['CHARGES'];
            $cotis_acc = $result['COTIS_ACC'];
            $cotis_serv = $result['COTIS_SERVICES'];
            $tva = $result['TVA'];
            $date_blocage = $result['DATE_BLOQUE'];
            $motif_blocage = $result['DATE_BLOQUE_MOTIF'];
            $cle_dispo = $result['CLE_LOGE_DISPO'];
            $ref_cle = $result['REF_CLE'];
            $qte_cle = $result['QTE_CLE_LOGE'];
            $qte_cle_bal = $result['QTE_CLE_BAL'];
            $ref_cle_bal = $result['REF_CLE_BAL'];
            if (isset($result['REF_CLE_BADGE']))
                $ref_cle_badge = $result['REF_CLE_BADGE'];
            if (isset($result['CLE_ENTREE_RESIDENCE']))
                $cle_residence = $result['CLE_ENTREE_RESIDENCE'];
            if (isset($result['CLE_LOCAL_VELOS']))
                $cle_local_velos = $result['CLE_LOCAL_VELOS'];
            if (isset($result['CLE_SALLE_COMMUNE']))
                $cle_salle_commune = $result['CLE_SALLE_COMMUNE'];
            if (isset($result['CLE_COMMENTAIRE']))
                $cle_commentaire = $result['CLE_COMMENTAIRE'];

            $logement->setResidence($residence);
            $logement->setBatiment($batiment);
            $logement->setNumero($numero);
            $logement->setCategorie($categorie);
            $logement->setSurface($surface);
            $logement->setLoyer($loyer);
            $logement->setCharges($charges);
            $logement->setCotisAcc($cotis_acc);
            $logement->setCotisServices($cotis_serv);
            $logement->setTva($tva);
            $logement->setCleDispo($cle_dispo);
            $logement->setRefCle($ref_cle);
            $logement->setQteCle($qte_cle);
            $logement->setQteCleBal($qte_cle_bal);
            $logement->setRefCleBal($ref_cle_bal);
            if (isset($result['REF_CLE_BADGE']))
                $logement->setRefCleBadge($ref_cle_badge);
            $logement->setCleResidence($cle_residence);
            $logement->setCleLocalVelo($cle_local_velos);
            $logement->setCleSalleCommune($cle_salle_commune);
            $logement->setCleCommentaire($cle_commentaire);
            switch ($type_logement) {
                case '0':
                    $logement->setTypeLogement('F1');
                    break;
                case '1':
                    $logement->setTypeLogement('F1 BIS');
                    break;
                case '2':
                    $logement->setTypeLogement('F2');
                    break;
                case '3':
                    $logement->setTypeLogement('F2 COLOCATION');
                    break;
                case '4':
                    $logement->setTypeLogement('F2 DUPLEX');
                    break;
                case '5':
                    $logement->setTypeLogement('F3 COLOCATION');
                    break;
                case '6':
                    $logement->setTypeLogement('DUPLEX');
                    break;
                case '7':
                    $logement->setTypeLogement('F3');
                    break;
                case '8':
                    $logement->setTypeLogement('F4');
                    break;
                case '9':
                    $logement->setTypeLogement('F5');
                    break;
                case '10':
                    $logement->setTypeLogement('F6');
                    break;
                case '11':
                    $logement->setTypeLogement('F7');
                    break;
                case '12':
                    $logement->setTypeLogement('F8');
                    break;
                case '13':
                    $logement->setTypeLogement('F4 COLOCATION');
                    break;
            }
            switch ($etage) {
                case '0':
                    $logement->setEtage('RDC');
                    break;
                case '1':
                    $logement->setEtage('RDC Bas');
                    break;
                case '2':
                    $logement->setEtage('RDC Haut');
                    break;
                case '3':
                    $logement->setEtage('1er');
                    break;
                case '4':
                    $logement->setEtage('2ème');
                    break;
                case '5':
                    $logement->setEtage('3ème');
                    break;
                case '6':
                    $logement->setEtage('4ème');
                    break;
                case '7':
                    $logement->setEtage('5ème');
                    break;
                case '8':
                    $logement->setEtage('6ème');
                    break;
                case '9':
                    $logement->setEtage('Combles');
                    break;
                case '10':
                    $logement->setEtage('7ème');
                    break;
            }
            switch ($type_lit) {
                case '0':
                    $logement->setTypeLit('Pas de sommier');
                    break;
                case '1':
                    $logement->setTypeLit('Sommier 1 place');
                    break;
                case '2':
                    $logement->setTypeLit('Sommier 2 places');
                    break;
            }
            switch ($situation) {
                case '0':
                    $logement->setSituation('Cours');
                    break;
                case '1':
                    $logement->setSituation('Jardin');
                    break;
                case '2':
                    $logement->setSituation('Parking');
                    break;
                case '3':
                    $logement->setSituation('Rue');
                    break;
                case '4':
                    $logement->setSituation('Espace vert');
                    break;
                case '5':
                    $logement->setSituation('2 côtés');
                    break;
                case '6':
                    $logement->setSituation('Traversans');
                    break;
                case '7':
                    $logement->setSituation('Cours nord');
                    break;
                case '8':
                    $logement->setSituation('Cours sud');
                    break;
                case '9':
                    $logement->setSituation('Cours ouest');
                    break;
                case '10':
                    $logement->setSituation('Cours est');
                    break;
            }
            $em2->persist($logement);
            $em2->flush();
            /* if ($existLoc) {
                 //DO NOTHING !
             } else {
                 $user = new Locataire();
                 //echo $result['COLUMN_NAME'].'<br>';

                 $id_adh = $result['ID_TYPE_ADH'];
                 $id_mrh = $result['ID_TYPE_MRH'];
                 $id_civ = $result['ID_CIV'];

                 $code_insee = $result['INSEE'];
                 $externe = $result['EXTERNE'];
                 if ($result['PASS_LOCATAIRE'] != '') {
                     $password = $encoder->encodePassword($user, $result['PASS_LOCATAIRE']);
                     $user->setPassword($password);
                 }
                 $nom = $result['NOM_LOCATAIRE'];
                 $prenom = $result['PRENOM_LOCATAIRE'];
                 $date_naissance = $result['DATE_NAISSANCE_LOCATAIRE'];
                 $ville_naissance = $result['VILLE_NAISSANCE_LOCATAIRE'];
                 $adresse = $result['NOUV_ADR_LOCATAIRE'];
                 $ville = $result['NOUV_VILLE_LOCATAIRE'];
                 $code_postal = $result['NOUV_CP_LOCATAIRE'];
                 $cpl_adresse = $result['NOUV_CPL_ADR_LOCATAIRE'];
                 $tel_fixe = $result['TEL_LOCATAIRE'];
                 $tel_mobile = $result['PORTABLE_LOCATAIRE'];
                 $commentaire = $result['COMMENTAIRE'];
                 $date_record = $result['DATE_SAISIE_LOCATAIRE'];
                 $user->setId($id);
                 $user->setIdAdh($id_adh);
                 $user->setIdMrh($id_mrh);
                 $user->setIdCiv($id_civ);
 //            CREATION PARENTS
                 $parents = new Tuteur();
                 $reqParent = "SELECT * FROM dbo.PARENTS WHERE ID_PARENTS = '$id_tuteur'";
                 $statement = $em->getConnection()->prepare($reqParent);
                 $statement->execute();
                 $resultsParent = $statement->fetchAll();

                 if (!$existPar && $resultsParent) {
                     $resultsParent = $resultsParent[0];
                     $parents->setId($resultsParent['ID_PARENTS']);
                     $parents->setNom($resultsParent['NOM_PARENTS']);
                     $parents->setPrenom($resultsParent['PRENOM_PARENTS']);
                     $parents->setAdresse($resultsParent['ADR_PARENTS']);
                     $parents->setCodePostal($resultsParent['CP_PARENTS']);
                     $parents->setCplAdresse($resultsParent['CPL_ADR_PARENTS']);
                     $parents->setVille($resultsParent['VILLE_PARENTS']);
                     $parents->setPays($resultsParent['PAYS_PARENTS']);
                     $parents->setDateNaissance($resultsParent['DATE_NAISSANCE_PARENT']);
                     $parents->setTelFixe($resultsParent['TEL_PARENTS']);
                     $parents->setTelMobile($resultsParent['PORTABLE_PARENTS']);
                     $parents->setEmail($resultsParent['MAIL_PARENTS']);
                     $em2->persist($parents);
                     $em2->flush();
                     $user->setParent($parents);
                 }
 //            FIN PARENTS


 //            CREATION GARANT
                 $reqGarant = "SELECT * FROM dbo.GARANT WHERE ID_GARANT = '$id_garant'";
                 $statement = $em->getConnection()->prepare($reqGarant);
                 $statement->execute();
                 $resultsGarant = $statement->fetchAll();
                 if (!$existGar && $resultsGarant) {
                     $garant = new Garant();

                     $resultsGarant = $resultsGarant[0];
                     $garant->setId($resultsGarant['ID_GARANT']);
                     $garant->setNom($resultsGarant['NOM_GARANT']);
                     $garant->setPrenom($resultsGarant['PRENOM_GARANT']);
                     $garant->setAdresse($resultsGarant['ADR_GARANT']);
                     $garant->setCodePostal($resultsGarant['CP_GARANT']);
                     $garant->setPays($resultsGarant['PAYS_GARANT']);
                     $garant->setCplAdresse($resultsGarant['CPL_ADR_GARANT']);
                     $garant->setVille($resultsGarant['VILLE_GARANT']);
                     $garant->setDateNaissance($resultsGarant['DATE_NAISSANCE_GARANT']);
                     $garant->setTelFixe($resultsGarant['TEL_GARANT']);
                     $garant->setTelMobile($resultsGarant['PORTABLE_GARANT']);
                     $garant->setEmail($resultsGarant['MAIL_GARANT']);
                     $em2->persist($garant);
                     $em2->flush();
                     $user->setGarant($garant);
 //            FIN GARANT
                 }

                 $user->setCodeInsee($code_insee);
                 $user->setExterne($externe);
                 if ($result['MAIL_LOCATAIRE']) {
                     $user->setEmail($email);
                 }

                 $user->setNom($nom);
                 $user->setPrenom($prenom);
                 $user->setDateNaissance($date_naissance);
                 $user->setVilleNaissance($ville_naissance);
                 $user->setVille($ville);
                 $user->setAdresse($adresse);
                 $user->setCplAdresse($cpl_adresse);
                 $user->setCodePostal($code_postal);
                 $user->setTelFixe($tel_fixe);
                 $user->setTelMobile($tel_mobile);
                 $user->setCommentaire($commentaire);
                 $user->setDateRecord($date_record);


                 $em2->persist($user);
                 $em2->flush();

             }*/

        }
        die;
    }
}
