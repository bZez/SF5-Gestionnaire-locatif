<?php

namespace App\Controller\Locataire;

use App\Entity\Contrat;
use App\Entity\EDL;
use App\Entity\Garant;
use App\Entity\Locataire;
use App\Entity\Logement;
use App\Entity\Sepa;
use App\Entity\Voeu;
use App\Repository\LocataireRepository;
use App\Repository\LogementRepository;
use App\Repository\ProspectRepository;
use App\Repository\ResidenceRepository;
use App\Repository\UserRepository;
use App\Repository\VoeuRepository;
use App\Service\FileUploader;
use App\Service\GetFileArray;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/",host="locataire.mgellogement.fr")
 */
class GlobalController extends AbstractController
{
    /**
     * @Route("/profile", name="locataire_home")
     */
    public function profile(Request $request, LocataireRepository $locataireRepository, ProspectRepository $prospectRepository)
    {
        $this->denyAccessUnlessGranted(['ROLE_LOCATAIRE', 'ROLE_PROSPECT']);
        if ($this->isGranted('ROLE_PROSPECT')) {
            $locataire = $prospectRepository->find($this->getUser());

        } else {
            $locataire = $locataireRepository->find($this->getUser());
        }

        /*$garant = $locataire->getGarant()->getNom();
        $tuteur = $locataire->getParent()->getNom();
        if ($tuteur != $garant) {
            $displayParent = true;
        } else {
            $displayParent = false;
        }*/
        /*if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $oldMobile = $locataire->getTelMobile();
            $oldFixe = $locataire->getTelFixe();
            $oldEmail = $locataire->getEmail();
            if (isset($_POST['telMobile'], $_POST['telFixe'], $_POST['email'])) {
                $newMobile = $_POST['telMobile'];
                $newFixe = $_POST['telFixe'];
                $newEmail = $_POST['email'];
                if ($newMobile !== $oldMobile && $newMobile == (int)$newMobile) {
                    $locataire->setTelMobile($newMobile);

                } elseif ($newFixe !== $oldFixe && $newFixe == (int)$newFixe) {
                    $locataire->setTelFixe($newFixe);

                } elseif ($newEmail !== $oldEmail) {
                    $locataire->setEmail($newEmail);
                }
                $entityManager->persist($locataire);
            }
            if (isset($_POST['typeTravaux'], $_POST['comTravaux']) && $_POST['typeTravaux'] !== null && $_POST['comTravaux'] !== null) {
                $type = $_POST['typeTravaux'];
                $com = $_POST['comTravaux'];
                $travaux = new Travaux();
                $travaux->setLocataire($this->getUser());
                $travaux->setLogement($this->getUser()->getLogement());
                $travaux->setType($type);
                $travaux->setCommentaire($com);
                $entityManager->persist($travaux);
            }
            $entityManager->flush();
        }*/

        return $this->render('esperso/profil.html.twig', [
            'locataire' => $locataire,
            'tab' => 'info',
        ]);

    }

    /**
     * @Route("/set/stautpro", name="set_statut_pro")
     */
    public function setStautPro()
    {
        $this->denyAccessUnlessGranted(['ROLE_LOCATAIRE', 'ROLE_PROSPECT']);
        $prospect = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $prospect->setStatutPro($_POST['statutPro']);
        $em->persist($prospect);
        $em->flush();
        return $this->redirectToRoute('locataire_home');
    }

    /**
     * @Route("/set/signelec", name="set_sign_elec")
     */
    public function setSignElec()
    {
        $this->denyAccessUnlessGranted(['ROLE_LOCATAIRE']);
        $locataire = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $contrat = $locataire->getLastContrat();
        $contrat->setSignElec($_POST['signElec']);
        $em->persist($contrat);
        $em->flush();
        return $this->redirectToRoute('mes-documents');
    }

    /**
     * @Route("/set/sepa", name="update_sepa")
     */
    public function setSepa()
    {
        $this->denyAccessUnlessGranted(['ROLE_LOCATAIRE']);
        $now = new \DateTime();
        $locataire = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $sepa = new Sepa();
        $sepa->setLocataire($locataire);
        $sepa->setIban($_POST['sepa']['iban']);
        $sepa->setBic($_POST['sepa']['bic']);
        if($_POST['sepa']['titulaire'] == 1) {
            $sepa->setTitulaire(1);
        } else {
            $sepa->setTitulaire(0);
            $sepa->setNomTitulaire($_POST['sepa']['titulaire']['nom']);
            $sepa->setAdrTitulaire($_POST['sepa']['titulaire']['adresse']);
            $sepa->setCpTitulaire($_POST['sepa']['titulaire']['cp']);
            $sepa->setVilleTitulaire($_POST['sepa']['titulaire']['ville']);
        }
        $sepa->setRum('PRV-MGEL-LGT-'.$now->format('Y').'-'.time());
        $em->persist($sepa);
        $em->flush();
        return $this->redirectToRoute('mes-documents');
    }

    /**
     * @Route("/set/coloc", name="set_coloc")
     */
    public function setColoc()
    {
        $this->denyAccessUnlessGranted(['ROLE_LOCATAIRE', 'ROLE_PROSPECT']);
        $prospect = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $prospect->setColocation($_POST['coloc']);
        $em->persist($prospect);
        $em->flush();
        return $this->redirectToRoute('locataire_home');
    }

    /**
     * @Route("/set/garant", name="update_garant")
     */
    public function updateGarant(Request $request)
    {
        $this->denyAccessUnlessGranted(['ROLE_LOCATAIRE', 'ROLE_PROSPECT']);
        if ($request->isMethod('POST')) {
            $posted = $request->request;
            $prospect = $this->getUser();
            $prospect->setNomGarant($posted->get('nomGarant'));
            $prospect->setPrenomGarant($posted->get('prenomGarant'));
            $prospect->setEmailGarant($posted->get('emailGarant'));
            $prospect->setTelephoneGarant($posted->get('telephoneGarant'));
            $prospect->setAdresseGarant($posted->get('adresseGarant'));
            $prospect->setCodePostalGarant($posted->get('codePostalGarant'));
            $prospect->setVilleGarant($posted->get('villeGarant'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($prospect);
            $em->flush();
            return $this->redirectToRoute('locataire_home');
        }
    }

    /**
     * @Route("/mon-logement", name="mon-logement")
     */
    public function monLogement()
    {
        $this->denyAccessUnlessGranted('ROLE_LOCATAIRE');
        $locataire = $this->getUser();
        return $this->render('esperso/profil.html.twig', [
            'locataire' => $locataire,
            'tab' => 'logement',
        ]);
    }

    /**
     * @Route("/mes-echances", name="mes-echeances")
     */
    public function mesEcheances(GetFileArray $getFileArray)
    {
        $this->denyAccessUnlessGranted('ROLE_LOCATAIRE');
        $locataire = $this->getUser();
        $userDir = $this->getParameter('echeances_dir') . '/'. $this->getUser()->getId();
        $contratDir = $this->getParameter('contrats_dir') . '/' . $this->getUser()->getId();
        if(file_exists($userDir))
        {
           $echs = $getFileArray->get_filelist_as_array($userDir);
        } else {
            $echs = null;
        }
        return $this->render('esperso/profil.html.twig', [
            'locataire' => $locataire,
            'tab' => 'echeances',
            'echs' => $echs,
        ]);
    }

    /**
     * @Route("/mes-documents", name="mes-documents")
     */
    public function mesDocuments(GetFileArray $getFileArray, Request $request, FileUploader $fileUploader)
    {
        $locataire = $this->getUser();
        $userDir = $this->getParameter('echeances_dir') . '/' . $this->getUser()->getId();
        $contratDir = $this->getParameter('contrats_dir') . '/' . $this->getUser()->getId();
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $fileUploader->setTargetDirectory($this->getParameter('datas_dir'));
            if ($request->files->get('cni')) {
                if ($locataire->getCni())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getCni());
                $locataire->setCni($fileUploader->upload($request->files->get('cni')));
            } else if ($request->files->get('cni_garant')) {
                if ($locataire->getCniGarant())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getCniGarant());
                $locataire->setCniGarant($fileUploader->upload($request->files->get('cni_garant')));
            } else if ($request->files->get('justif_dom')) {
                if ($locataire->getJustifDom())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getJustifDom());
                $locataire->setJustifDom($fileUploader->upload($request->files->get('justif_dom')));
            } else if ($request->files->get('livret')) {
                if ($locataire->getLivret())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getLivret());
                $locataire->setLivret($fileUploader->upload($request->files->get('livret')));
            } else if ($request->files->get('bull_sal')) {
                if ($locataire->getBullSal())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getBullSal());
                $locataire->setBullSal($fileUploader->upload($request->files->get('bull_sal')));
            } else if ($request->files->get('avis_imp')) {
                if ($locataire->getAvisImp())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getAvisImp());
                $locataire->setAvisImp($fileUploader->upload($request->files->get('avis_imp')));
            } else if ($request->files->get('justif_scol')) {
                if ($locataire->getJustifScol())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getJustifScol());
                $locataire->setJustifScol($fileUploader->upload($request->files->get('justif_scol')));
            } else if ($request->files->get('contrat_trav')) {
                if ($locataire->getContratTrav())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getContratTrav());
                $locataire->setContratTrav($fileUploader->upload($request->files->get('contrat_trav')));
            } else if ($request->files->get('bull_sal_loc')) {
                if ($locataire->getBullSalLoc())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getBullSalLoc());
                $locataire->setBullSalLoc($fileUploader->upload($request->files->get('bull_sal_loc')));
            } else if ($request->files->get('avis_imp_loc')) {
                if ($locataire->getAvisImpLoc())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getAvisImpLoc());
                $locataire->setAvisImpLoc($fileUploader->upload($request->files->get('avis_imp_loc')));
            } else if ($request->files->get('rib')) {
                if ($locataire->getRib())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getRib());
                $locataire->setRib($fileUploader->upload($request->files->get('rib')));
            } else if ($request->files->get('sepa')) {
                if ($locataire->getSepa())
                    unlink($this->getParameter('datas_dir') . '/' . $locataire->getSepa());
                $locataire->setSepa($fileUploader->upload($request->files->get('sepa')));
            } else if ($request->files->get('photo')) {
                if ($locataire->getPhoto())
                    unlink($this->getParameter('datas_dir') . '/photos/' . $locataire->getPhoto());
                $fileUploader->setTargetDirectory($this->getParameter('datas_dir') . '/photos/');
                $locataire->setPhoto($fileUploader->upload($request->files->get('photo')));
            }
            $em->persist($locataire);
            $em->flush();
        }
        return $this->render('esperso/profil.html.twig', [
            'locataire' => $locataire,
            'tab' => 'documents',
            'contrat' => $getFileArray->get_filelist_as_array($contratDir)
        ]);
    }

    /**
     * @Route("/demande", name="demande")
     */
    public function demande()
    {
        $this->denyAccessUnlessGranted('ROLE_LOCATAIRE');
        $locataire = $this->getUser();

        return $this->render('esperso/profil.html.twig', [
            'locataire' => $locataire,
            'tab' => 'demande',
        ]);
    }

    /**
     * @Route("/mon-voeu", name="mon-voeu")
     */
    public function monVoeu(VoeuRepository $voeuRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_LOCATAIRE');
        $locataire = $this->getUser();
        $year =date('Y', strtotime('+1 year'));
        if (isset($_POST['voeu'])) {
            $entityManager = $this->getDoctrine()->getManager();
            if($locataire->getLogement()->getVoeu()){
                $oldVoeu = $locataire->getLogement()->getVoeu()->getSouhait();
                $newVoeu = $_POST['voeu'];

                if ($newVoeu !== $oldVoeu) {
                    $voeu = $locataire->getLogement()->getVoeu();
                    $voeu->setSouhait($_POST['voeu']);
                }
            } else {
                $voeu = new Voeu();
                $voeu->setSouhait($_POST['voeu']);
                $voeu->setLogement($locataire->getLogement());
                $voeu->setAnnee($year);
                $locataire->setVoeu($voeu);
            }

            $entityManager->persist($voeu);
            $entityManager->persist($locataire);
            $entityManager->flush();
            dump($voeu);
            die;
        }
        return $this->render('esperso/profil.html.twig', [
            'locataire' => $locataire,
            'tab' => 'voeu',
        ]);
    }


    /**
     * @Route("/record")
     */
    public function setRecord(LocataireRepository $locataireRepository,UserRepository $repository)
    {
        $locataires = $locataireRepository->findAll();
        $em = $this->getDoctrine()->getManager();
        $user = $repository->find(1);
        foreach ($locataires as $locataire)
        {
            $locataire->setRecordBy($user);
            $em->persist($locataire);
        }
        $em->flush();
    }

    /**
     * @Route("/trans")
     */
    public function trans(UserPasswordEncoderInterface $encoder, LogementRepository $logementRepository,LocataireRepository $locataireRepository)
    {
        $old_gest = $this->getDoctrine()->getManager('old_gest');
        $new_gest = $this->getDoctrine()->getManager();
        $req = "SELECT LOGEMENT.ID_LOGEMENT, DATE_FIN_CONTRAT,DATE_DEB_CONTRAT,DATE_RDV_SIGN_CONTRAT,STATUT_SIGN_CONTRAT,LOCATAIRE.*,GARANT.ID_CIV AS ID_CIV_GARANT,GARANT.*,CONTRAT.* FROM MGEL_LOGEMENT.dbo.LOGEMENT as LOGEMENT
						INNER JOIN MGEL_LOGEMENT.dbo.OCCUPE as OCCUPE ON OCCUPE.ID_LOGEMENT = LOGEMENT.ID_LOGEMENT
						INNER JOIN MGEL_LOGEMENT.dbo.CONTRAT as CONTRAT ON CONTRAT.ID_CONTRAT = OCCUPE.ID_CONTRAT
						INNER JOIN MGEL_LOGEMENT.dbo.LOCATAIRE as LOCATAIRE ON LOCATAIRE.ID_LOCATAIRE = OCCUPE.ID_LOCATAIRE
						INNER JOIN MGEL_LOGEMENT.dbo.GARANT as GARANT ON LOCATAIRE.ID_GARANT = GARANT.ID_GARANT
					WHERE DATE_FIN_CONTRAT >= '01/04/2019'
					AND LOGEMENT.ID_RESIDENCE <> 20
					AND LOGEMENT.ID_RESIDENCE <> 22";
        $statement = $old_gest->getConnection()->prepare($req);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach ($results as $keys => $vals) {
            $id_logement = $vals['ID_LOGEMENT'];
            if($locataireRepository->find($vals['ID_LOCATAIRE']) || $locataireRepository->findOneBy(['email'=>$vals['MAIL_LOCATAIRE']])){
                $loc = $locataireRepository->findOneBy(['email'=>$vals['MAIL_LOCATAIRE']]);
                $contrat = new Contrat();
                $date_fin_contrat = new \DateTime($vals['DATE_FIN_CONTRAT']);
                $date_deb_contrat = new \DateTime($vals['DATE_DEB_CONTRAT']);
                $contrat->setDuree(12);
                $statut_sign_contrat = $vals['STATUT_SIGN_CONTRAT'];
                $date_rdv_sign_contrat = new \DateTime($vals['DATE_RDV_SIGN_CONTRAT']);
                $contrat->setLocataire($loc);
                $contrat->setLogement($logementRepository->find($id_logement));
                $contrat->setDebut($date_deb_contrat);
                $contrat->setFin($date_fin_contrat);
                $contrat->setSignature($statut_sign_contrat);
                $contrat->setDateSignature($date_rdv_sign_contrat);
                $new_gest->persist($contrat);
                $new_gest->flush();
                continue;
            }

            $loc = new Locataire();

            $id_logement = $vals['ID_LOGEMENT'];
            if ($logementRepository->find($id_logement))
                $loc->addLogement($logementRepository->find($id_logement));
            else
                continue;

            $id_locataire = $vals['ID_LOCATAIRE'];
            $loc->setId($id_locataire);

            $id_type_adh = $vals['ID_TYPE_ADH'];
            if ($id_type_adh == '0') {
                $loc->setTypeMrh('Non adhérent');
            }
            elseif ($id_type_adh == '1') {
                $loc->setTypeMrh('Adhérent MGEL');
            }
            else {
                $loc->setTypeMrh('MGEL Logement');
            }


            $id_type_mrh = $vals['ID_TYPE_MRH'];
            if ($id_type_mrh === '0')
                $id_type_mrh = 'Autre';
            elseif ($id_type_mrh === '1')
                $id_type_mrh = 'Vital Assur';
            $loc->setTypeMrh($id_type_mrh);

            $insee = $vals['INSEE'];
            $loc->setCodeInsee($insee);

            /*$numcle = $vals['NUMCLE'];*/

            $id_civ = $vals['ID_CIV'];
            /*$id_parents = $vals['ID_PARENTS'];*/


            //GARANT
            $garant = new Garant();
            $id_garant = $vals['ID_GARANT'];
            $garant->setId($id_garant);

            $nom_garant = $vals['NOM_GARANT'];
            $garant->setNom($nom_garant);

            $prenom_garant = $vals['PRENOM_GARANT'];
            $garant->setPrenom($prenom_garant);

            $adr_garant = $vals['ADR_GARANT'];
            $garant->setAdresse($adr_garant);

            $cpl_adr_garant = $vals['CPL_ADR_GARANT'];
            $garant->setCplAdresse($cpl_adr_garant);

            $cp_garant = $vals['CP_GARANT'];
            $garant->setCodePostal($cp_garant);

            $ville_garant = $vals['VILLE_GARANT'];
            $garant->setVille($ville_garant);

            $pays_garant = $vals['PAYS_GARANT'];
            $garant->setPays($pays_garant);

            $date_naissance_garant = new \DateTime($vals['DATE_NAISSANCE_GARANT']);
            $garant->setDateNaissance($date_naissance_garant);

            $id_civ_garant = $vals['ID_CIV_GARANT'];
            if ($id_civ_garant == '1') {
                $garant->setCivilite('M');
            } else {
                $garant->setCivilite('MME');
            }

            $tel_garant = $vals['TEL_GARANT'];
            $garant->setTelFixe($tel_garant);

            /*$tel2_garant = $vals['TEL2_GARANT'];*/

            $portable_garant = $vals['PORTABLE_GARANT'];
            $garant->setTelMobile($portable_garant);

            $mail_garant = $vals['MAIL_GARANT'];
            $garant->setEmail($mail_garant);

            $new_gest->persist($garant);


            $loc->setGarant($garant);

            $externe = $vals['EXTERNE'];
            $loc->setExterne($externe);

            /*$pass_locataire = $vals['PASS_LOCATAIRE'];*/
            if ($vals['PASS_LOCATAIRE'] != '') {
                $password = $encoder->encodePassword($loc, $vals['PASS_LOCATAIRE']);
                $loc->setPassword($password);
            }
            $nom_locataire = $vals['NOM_LOCATAIRE'];
            $loc->setNom($nom_locataire);

            $prenom_locataire = $vals['PRENOM_LOCATAIRE'];
            $loc->setPrenom($prenom_locataire);

            /*$nouv_adr_locataire = $vals['NOUV_ADR_LOCATAIRE'];
            $nouv_cpl_adr_locataire = $vals['NOUV_CPL_ADR_LOCATAIRE'];
            $nouv_cp_locataire = $vals['NOUV_CP_LOCATAIRE'];
            $nouv_ville_locataire = $vals['NOUV_VILLE_LOCATAIRE'];*/

            /* $tel2_locataire = $vals['TEL2_LOCATAIRE'];*/

            $tel_locataire = $vals['TEL_LOCATAIRE'];
            $loc->setTelFixe($tel_locataire);

            $portable_locataire = $vals['PORTABLE_LOCATAIRE'];
            $loc->setTelMobile($portable_locataire);

            $mail_locataire = $vals['MAIL_LOCATAIRE'];
            $loc->setEmail($mail_locataire);

            /*$repf_different_garant = $vals['REPF_DIFFERENT_GARANT'];
            $repf_nom = $vals['REPF_NOM'];
            $repf_prenom = $vals['REPF_PRENOM'];
            $repf_date_naissance = $vals['REPF_DATE_NAISSANCE'];*/

            $commentaire = $vals['COMMENTAIRE'];
            $loc->setCommentaire($commentaire);

            $date_naissance_locataire = new \DateTime($vals['DATE_NAISSANCE_LOCATAIRE']);
            $loc->setDateNaissance($date_naissance_locataire);

            $ville_naissance_locataire = $vals['VILLE_NAISSANCE_LOCATAIRE'];
            $loc->setVilleNaissance($ville_naissance_locataire);

            $date_saisie_locataire = new \DateTime($vals['DATE_SAISIE_LOCATAIRE']);
            $loc->setDateRecord($date_saisie_locataire);

            /*$date_saisie_locataire_users = $vals['DATE_SAISIE_LOCATAIRE_USERS'];
            $date_voeu_locataire = $vals['DATE_VOEU_LOCATAIRE'];
            $type_voeu_locataire = $vals['TYPE_VOEU_LOCATAIRE'];
            $date_rentree_univ = $vals['DATE_RENTREE_UNIV'];*/

            $date_rdv_edle = new \DateTime($vals['DATE_RDV_EDLE']);
            $edl = new EDL();
            $edl->setLogement($logementRepository->find($id_logement));
            $edl->setType('EDLE');
            $edl->setDate($date_rdv_edle);
            $edl->setLocataire($loc);
            $edl->setStatut(1);
            $new_gest->persist($edl);

            /*$date_voeux_bloque = $vals['DATE_VOEUX_BLOQUE'];
            $date_voeux_bloque_users = $vals['DATE_VOEUX_BLOQUE_USERS'];*/

            $cnil_mgel = $vals['CNIL_MGEL'];
            $cnil_partenaires = $vals['CNIL_PARTENAIRES'];


            if ($cnil_mgel === 'O') {
                $loc->setCnilMgel(true);
            } else {
                $loc->setCnilMgel(false);
            }
            if ($cnil_partenaires === 'O') {
                $loc->setCnilPartenaires(true);
            } else {
                $loc->setCnilPartenaires(false);
            }
            if ($id_civ == '1') {
                $loc->setCivilite('M');
            } else {
                $loc->setCivilite('MME');
            }
            $loc->setChequeFrais(1);

            $date_recep_dossier = new \DateTime($vals['DATE_VALIDATION_DER_DOC']);
            $loc->setDateRecepDossier($date_recep_dossier);

            $new_gest->persist($loc);

            $contrat = new Contrat();
            $date_fin_contrat = new \DateTime($vals['DATE_FIN_CONTRAT']);
            $date_deb_contrat = new \DateTime($vals['DATE_DEB_CONTRAT']);
            $contrat->setDuree(12);
            $statut_sign_contrat = $vals['STATUT_SIGN_CONTRAT'];
            $date_rdv_sign_contrat = new \DateTime($vals['DATE_RDV_SIGN_CONTRAT']);
            $contrat->setLocataire($loc);
            $contrat->setLogement($logementRepository->find($id_logement));
            $contrat->setDebut($date_deb_contrat);
            $contrat->setFin($date_fin_contrat);
            $contrat->setSignature(1);
            $contrat->setDateSignature($date_rdv_sign_contrat);
            $new_gest->persist($contrat);

            $new_gest->flush();


            /*foreach ($vals as $key => $val) {
                echo '$' . strtolower($key) . ' = $vals[\'' . $key . '\'];<br>';
            }*/
        }
        return new Response('Tout est ok !');
    }
}
