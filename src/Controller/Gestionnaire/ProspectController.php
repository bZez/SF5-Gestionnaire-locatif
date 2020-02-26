<?php

namespace App\Controller\Gestionnaire;

use App\Controller\MailingController;
use App\Entity\Contrat;
use App\Entity\Garant;
use App\Entity\Locataire;
use App\Entity\Logement;
use App\Entity\Prospect;
use App\Entity\Stats;
use App\Entity\Todo;
use App\Form\ProspectType;
use App\Repository\LogementRepository;
use App\Repository\ProspectRepository;
use App\Repository\ResidenceRepository;
use App\Repository\VilleRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Mailjet\Resources;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/prospect",host="gest.mgellogement.fr")
 */
class ProspectController extends AbstractController
{
    /**
     * @Route("/", name="prospect_index", methods={"GET","POST"})
     */
    public function index(ProspectRepository $prospectRepository, VilleRepository $villeRepository, ResidenceRepository $residenceRepository): Response
    {

//        TODO AJOUTER SI COLOC & ETRANGER
//        TODO: LIMITER LA TAILLE DES PIECES JOINTES
//        TODO: PIECES A VALIDER PAR MGEL LOGEMENT
        //TODO: MAILS PIECES MANQUANTES,ACCEPT, REFUS
        //TODO: AJOUTER LEGENDE -

        /*
           Orange : Dossier reçu, en cours de traitement = manque piece ou aucune piece valide
          Vert : Dossier accepté - Mail envoyé (TOUT LES DOCS DOIVENT ETRE VALIDés)
          Cyan : Dossier accepté (plusieurs demandes) - = Dossier vert mais plusieures villes
          Marron : Dossier refusé
          Rouge : DL annulée (DEPUIS ESPACE OU GEST) - SPECIFIE DATE ET RENVOI DU CHEQUE
        */

//        TODO: COURRIER TYPE ANNULATION


        $villes = $villeRepository->findAll();
        $residences = array();
        foreach ($villes as $ville) {
            $residences[$ville->getNom()] = $residenceRepository->findBy(['ville' => $ville]);
        }

        return $this->render('gestionnaire/prospect/index.html.twig', [
            'prospects' => $prospectRepository->findAll(),
            'villes' => $villes,
            'residences' => $residences,
        ]);
    }

    /**
     * @Route("/new", name="prospect_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader, UserPasswordEncoderInterface $encoder, MailingController $mailer): Response
    {
        //        TODO: CREATION ESPACE PROSPECT - POSSIBILITE DE REMPLIR LES CHAMPS 'ORDRE GENERALE DL'

        $prospect = new Prospect();
        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileUploader->setTargetDirectory($this->getParameter('datas_dir'));
            $cni = $request->files->get('prospect')['documents']['cni'];
            $cni_garant = $request->files->get('prospect')['documents']['cni_garant'];
            $livret = $request->files->get('prospect')['documents']['livret'];
            $justif_dom = $request->files->get('prospect')['documents']['justif_dom'];
            $bull_sal = $request->files->get('prospect')['documents']['bull_sal'];
            $avis_imp = $request->files->get('prospect')['documents']['avis_imp'];
            $justif_bourse = $request->files->get('prospect')['documents']['justif_bourse'];

            if ($justif_bourse != null) {
                $justif_bourse_name = $fileUploader->upload($justif_bourse);
                $prospect->setJustifBourse($justif_bourse_name);
            }

            if ($cni != null) {
                $cni_name = $fileUploader->upload($cni);
                $prospect->setCni($cni_name);
            }
            if ($cni_garant != null) {
                $cni_garant_name = $fileUploader->upload($cni_garant);
                $prospect->setCniGarant($cni_garant_name);
            }
            if ($livret != null) {
                $livret_name = $fileUploader->upload($livret);
                $prospect->setLivret($livret_name);

            }
            if ($justif_dom != null) {
                $justif_dom_name = $fileUploader->upload($justif_dom);
                $prospect->setJustifDom($justif_dom_name);

            }
            if ($bull_sal != null) {
                $bull_sal_name = $fileUploader->upload($bull_sal);
                $prospect->setBullSal($bull_sal_name);

            }
            if ($avis_imp != null) {
                $avis_imp_name = $fileUploader->upload($avis_imp);
                $prospect->setAvisImp($avis_imp_name);
            }

            $prospect->setPassword(
                $encoder->encodePassword($prospect, $prospect->getPlainPassword())
            );

            $mailer->mailProspect($prospect);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prospect);
            /*
                        $stats = new Stats();
                        $now = new \DateTime('now');
                        $stats->setAnnee($now->format('Y'));
                        $stats->setMois($now->format('m'));
                        $stats->setJour($now->format('d'));
                        $stats->setEntite('Prospect');
                        $stats->setResidence($prospect->getResidences()[0]);
                        $entityManager->persist($stats);*/

            $entityManager->flush();

            return $this->redirectToRoute('prospect_index');
        }

        return $this->render('gestionnaire/prospect/new.html.twig', [
            'prospect' => $prospect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prospect_show", methods={"GET"})
     */
    public function show(ProspectRepository $prospectRepository, $id, MailingController $mailer): Response
    {
        return $this->render('gestionnaire/prospect/show.html.twig', [
            'prospect' => $prospectRepository->findOne($id),
        ]);
    }

    /**
     * @Route("/{id}/relance", name="prospect_relance")
     */
    public function relance(Prospect $prospect, MailingController $mailer)
    {
        $mailer->mailRelanceDoc($prospect);
        $this->addFlash('success', "<i class='fa fas fa-envelope'></i> Mail de relance envoyé avec succès !");
        return $this->redirectToRoute('prospect_show', ['id' => $prospect->getId()]);
    }

    /**
     * @Route("/{id}/r/{name}/{doc}", name="doc_remover", methods={"GET"})
     */
    public function remove(ProspectRepository $prospectRepository, $id, $name, $doc): Response
    {
        $prospect = $prospectRepository->findOne($id);
        if ($name == 'cni')
            $prospect->setCni('');
        elseif ($name == 'justifBourse')
            $prospect->setJustifBourse('');
        elseif ($name == 'cheque')
            $prospect->ChequeFrais('');
        elseif ($name == 'cni_garant')
            $prospect->setCniGarant('');
        elseif ($name == 'livret')
            $prospect->setLivret('');
        elseif ($name == 'justifDom')
            $prospect->setJustifDom('');
        elseif ($name == 'bullSal')
            $prospect->setBullSal('');
        elseif ($name == 'bullSalLoc')
            $prospect->setBullSalLoc('');
        elseif ($name == 'avisImp')
            $prospect->setAvisImp('');
        elseif ($name == 'avisImpLoc')
            $prospect->setAvisImpLoc('');
        elseif ($name == 'contratTrav')
            $prospect->setContratTrav('');
        elseif ($name == 'justifScol')
            $prospect->setJustifScol('');
        elseif ($name == 'rib')
            $prospect->setRib('');
        elseif ($name == 'photo')
            $prospect->setPhoto('');
        elseif ($name == 'sepa')
            $prospect->setSepa('');
        $em = $this->getDoctrine()->getManager();
        $em->persist($prospect);
        $em->flush();
        unlink($this->getParameter('datas_dir') . '/' . $doc);
        $this->addFlash('success', 'Document supprimé !');
        return $this->redirectToRoute('prospect_show', ['id' => $id]);
    }


    /**
     * @Route("/{id}/edit", name="prospect_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Prospect $prospect, ResidenceRepository $residenceRepository, FileUploader $fileUploader): Response
    {
        $old_cni = $prospect->getCni();
        $old_cni_garant = $prospect->getCniGarant();
        $old_justif_bourse = $prospect->getJustifBourse();
        $old_justif_dom = $prospect->getJustifDom();
        $old_livret = $prospect->getLivret();
        $old_bull_sal = $prospect->getBullSal();
        $old_avis_imp = $prospect->getAvisImp();
        $old_pass = $prospect->getPassword();
        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$file = $request->files->get('prospect')['documents']['cni']) {
                $prospect->setCni($old_cni);
            } else {
                $file_name = $fileUploader->upload($file);
                $prospect->setCni($file_name);
            }
            if (!$file = $request->files->get('prospect')['documents']['cni_garant']) {
                $prospect->setCniGarant($old_cni_garant);
            } else {
                $file_name = $fileUploader->upload($file);
                $prospect->setCniGarant($file_name);
            }
            if (!$file = $request->files->get('prospect')['documents']['justif_bourse']) {
                $prospect->setJustifBourse($old_justif_bourse);
            } else {
                $file_name = $fileUploader->upload($file);
                $prospect->setJustifBourse($file_name);
            }
            if (!$file = $request->files->get('prospect')['documents']['justif_dom']) {
                $prospect->setJustifDom($old_justif_dom);
            } else {
                $file_name = $fileUploader->upload($file);
                $prospect->setJustifDom($file_name);
            }
            if (!$file = $request->files->get('prospect')['documents']['livret']) {
                $prospect->setLivret($old_livret);
            } else {
                $file_name = $fileUploader->upload($file);
                $prospect->setLivret($file_name);
            }
            if (!$file = $request->files->get('prospect')['documents']['bull_sal']) {
                $prospect->setBullSal($old_bull_sal);
            } else {
                $file_name = $fileUploader->upload($file);
                $prospect->setBullSal($file_name);
            }
            if (!$file = $request->files->get('prospect')['documents']['avis_imp']) {
                $prospect->setAvisImp($old_avis_imp);
            } else {
                $file_name = $fileUploader->upload($file);
                $prospect->setAvisImp($file_name);
            }
            $prospect->setPassword($old_pass);
            $this->getDoctrine()->getManager()->persist($prospect);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prospect_show', [
                'id' => $prospect->getId(),
            ]);
        }

        return $this->render('gestionnaire/prospect/edit.html.twig', [
            'prospect' => $prospect,
            'residences' => $residenceRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prospect_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Prospect $prospect): Response
    {
        if ($this->isCsrfTokenValid('delete' . $prospect->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prospect);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prospect_index');
    }


    /**
     * @Route("/{id}/_", name="prospect_get_logement")
     */
    public function getLogement(LogementRepository $logementRepository)
    {
        $residence_id = $_POST['residence'];
        $logements = $logementRepository->findLibreByResidenceWithoutDate($residence_id);
        $logements_array = array();
        foreach ($logements as $logement) {
            if (!$logement->getVoeu() || $logement->getVoeu()->getSouhait() == 'PART') {
                array_push($logements_array, array(
                    'id' => $logement->getId(),
                    'infos' => $logement->getInfos(),
                ));
            }
        }
        return new JsonResponse(array(
            'status' => 'OK',
            'logements' => $logements_array),
            200);
    }

    /**
     * @Route("/attribution/{id}/{logement}", name="prospect_attribution")
     */
    public function attribuerLogement(Request $request, Prospect $prospect, LogementRepository $logementRepository, $logement): Response
    {
//        TODO: CREATION FICHE PROSPECT
//        TODO CREATION ZIP DES PIECES
//        TODO: CREATION BOUTON IMPRESSION DOCS :  DOCS PROSPECT + FICHE PROSPECT
//        TODO: SELECTION SIGN ELEC OU PAPIER
        $logement = $logementRepository->find($logement);
        $em = $this->getDoctrine()->getManager();
        $locataire = new Locataire();
        $garant = new Garant();
        $contrat = new Contrat();

//        LOCATAIRE
        $locataire->setCivilite($prospect->getCivilite());
        $locataire->setNom($prospect->getNom());
        $locataire->setPrenom($prospect->getPrenom());
        $locataire->setTelMobile($prospect->getTelephone());
        $locataire->setEmail($prospect->getEmail());
        $locataire->setDateNaissance($prospect->getDateNaissance());
        $locataire->setAdresse($prospect->getAdresse());
        $locataire->setVille($prospect->getVille());
        $locataire->setCodePostal($prospect->getCodePostal());
        $locataire->setColocation($prospect->getColocation());
        $locataire->setPrioritaire($prospect->getPrioritaire());
        $locataire->setEtranger($prospect->getEtranger());
        $locataire->setCnilMgel($prospect->getPromoMgel());
        $locataire->setCnilPartenaires($prospect->getPromoPartenaire());
        $locataire->addLogement($logement);
        $locataire->setDateAccep($prospect->getDateAccep());
        $locataire->setDateRecepDossier($prospect->getDateRecepDossier());
        $locataire->setStatutPro($prospect->getStatutPro());


//        GARANT
        $garant->setCivilite($prospect->getCiviliteGarant());
        $garant->setNom($prospect->getNomGarant());
        $garant->setPrenom($prospect->getPrenomGarant());
        $garant->setDateNaissance($prospect->getDateNaissanceGarant());
        $garant->setAdresse($prospect->getAdresseGarant());
        $garant->setVille($prospect->getVilleGarant());
        $garant->setCodePostal($prospect->getCodePostalGarant());
        $garant->setTelMobile($prospect->getTelephoneGarant());
        $garant->setEmail($prospect->getEmailGarant());

//        DOCUMENTS GARANT
        $garant->setCni($prospect->getCniGarant());
        $garant->setLivret($prospect->getLivret());
        $garant->setJustifDom($prospect->getJustifDom());
        $garant->setBullSal($prospect->getBullSal());
        $garant->setAvisImp($prospect->getAvisImp());
        $em->persist($garant);
        $em->flush();

        $locataire->setGarant($garant);

//        DOCUMENTS LOCATAIRE
        $locataire->setCni($prospect->getCni());
        $locataire->setChequeFrais($prospect->getChequeFrais());
        $locataire->setJustifBourse($prospect->getJustifBourse());
        $locataire->setRib($prospect->getRib());
        /*$locataire->setSepa($prospect->getSepa());*/
        $locataire->setPhoto($prospect->getPhoto());
        $locataire->setAvisImp($prospect->getAvisImpLoc());
        $locataire->setBullSal($prospect->getBullSalLoc());
        $locataire->setContratTrav($prospect->getContratTrav());
        $locataire->setJustifScol($prospect->getJustifScol());
        $locataire->setRecordBy($this->getUser());

        $locataire->setPassword($prospect->getPassword());

        $em->persist($locataire);

//        CONTRAT
        $contrat->setLocataire($locataire);
        $contrat->setLogement($logement);
        $contrat->setGenBy($this->getUser());
        $contrat->setDebut($prospect->getDateEntree());
        $date_entree = $prospect->getDateEntree()->format('Y-m-d');
        if ($_POST['duree'] && $_POST['loyer'] && $_POST['charges'] && $_POST['cotisAcc'] && $_POST['cotisServices']) {
            $date_sortie = strtotime(date("Y-m-d", strtotime($date_entree)) . " +" . $_POST['duree'] . " month");
            $date_sortie = date('Y-m-d', $date_sortie);
            $contrat->setFin(new \DateTime($date_sortie));
            $contrat->setDuree($_POST['duree']);
            $contrat->setChargesPerso($_POST['charges']);
            $contrat->setLoyerPerso($_POST['loyer']);
            $contrat->setCotisAccPerso($_POST['cotisAcc']);
            $contrat->setCotisServicesPerso($_POST['cotisServices']);
        } else {
            $date_sortie = strtotime(date("Y-m-d", strtotime($date_entree)) . " +365 day");
            $date_sortie = date('Y-m-d', $date_sortie);
            $contrat->setFin(new \DateTime($date_sortie));
            $contrat->setDuree(12);
        }


        $em->persist($contrat);

        $now = new \DateTime('now');

        if ($contrat->getDebut() === $now->format('Y-m-d')) {
            $logement->setOccupation(1);
            $logement->setDateLibre($contrat->getFin());
            $em->persist($logement);
        }
        $todo = new Todo();
        $todo->setLocataire($locataire);
        $todo->setLogement($logement);
        $todo->setDateEntree($contrat->getDebut());
        $todo->setDateSortie($contrat->getFin());
        $em->persist($todo);

        $em->flush();
        $contrat->setPdf($locataire->getLogement()->getNumero() . "_" . $locataire->getId() . "_" . $contrat->getId() . ".pdf");
        $em->persist($contrat);
        /* // Configure Dompdf according to your needs
         $pdfOptions = new Options();
         $pdfOptions->set('defaultFont', 'Arial');

         // Instantiate Dompdf with our options
         $dompdf = new Dompdf($pdfOptions);

         // Retrieve the HTML generated in our twig file
         $html = $this->renderView('gestionnaire/pdf/contrat-vierge.html.twig', [
             'locataire' => $locataire,
             'logement' => $locataire->getLogement(),
             'garant' => $locataire->getGarant(),
             'residence' => $locataire->getLogement()->getResidence(),
             'contrat' => $contrat        ]);

         // Load HTML to Dompdf
         $dompdf->loadHtml($html);

         // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
         $dompdf->setPaper('A4', 'portrait');

         // Render the HTML as PDF
         $dompdf->render();

         // Store PDF Binary Data
         $output = $dompdf->output();

         // In this case, we want to write the file in the public directory
         $publicDirectory = $this->getParameter('kernel.project_dir'). '/public/_datas_/_contrats_';
         // e.g /var/www/project/public/mypdf.pdf
         $pdfFilepath =  $publicDirectory ."/". $locataire->getLogement()->getNumero()."_".$locataire->getId()."_".$contrat->getId().".pdf";

         // Write file to the desired path
         file_put_contents($pdfFilepath, $output);*/

        $em->remove($prospect);
        $em->flush();

        return $this->redirectToRoute('locataire_show', ['id' => $locataire->getId()]);
    }


    /**
     * @Route("/update",name="update")
     */
    public function updateProspect(Request $request, ProspectRepository $prospectRepository)
    {
        if (isset($request->request)) {
            $id = $request->request->get('id');
            $utile = $request->request->get('utile');
            $prospect = $prospectRepository->find($id);
            switch ($utile) {
                case "prioritaire":
                    $prospect->setPrioritaire();
                    break;
                case "colocation":
                    $prospect->setColocation();
                    break;
                case "etranger":
                    $prospect->setEtranger();
                    break;
                case "cheque":
                    $prospect->setChequeFrais();
                    break;
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($prospect);
            $em->flush();
            return $this->render('gestionnaire/prospect/show.html.twig', [
                'prospect' => $prospect,
            ]);
        }
    }

    /**
     * @Route("/dossier",name="update_dossier",host="{subdomain}.mgellogement.fr",defaults={"subdomain"="gest"},requirements={"subdomain"="gest|locataire"})
     */
    public function updateDossier(Request $request, ProspectRepository $prospectRepository)
    {
        if (isset($request->request)) {
            $id = $request->request->get('id');
            $statut = $request->request->get('statut');
            $em = $this->getDoctrine()->getManager();
            $prospect = $prospectRepository->find($id);
            $prospect->setStatut($statut);
            if ($statut == 'ACCEPTE') {
                $now = new \DateTime('now');
                $user = $this->getUser();
                $prospect->setDateAccep($now);
                $prospect->setAcceptBy($user);
            }
            if ($statut == 'TRANSMIS') {
                $now = new \DateTime('now');
                $prospect->setDateRecepDossier($now);
                $em->persist($prospect);
                $em->flush();
                return $this->redirectToRoute('mes-documents');
            }
            if ($statut == 'ANNULE') {
                $now = new \DateTime('now');
                $prospect->setDateRefus($now);
                $prospect->setRefusePar($this->getUser());
            }
            $em->persist($prospect);
            $em->flush();
            return $this->render('gestionnaire/prospect/show.html.twig', [
                'prospect' => $prospect,
            ]);
        }
    }

    public function generatePassword()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz$';
// Output: 54esmdr0qf
        return substr(str_shuffle($permitted_chars), 0, 10);
    }
}
