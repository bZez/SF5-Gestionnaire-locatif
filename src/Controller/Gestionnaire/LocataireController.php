<?php

namespace App\Controller\Gestionnaire;

use App\Entity\Garant;
use App\Entity\Locataire;
use App\Form\GarantType;
use App\Form\LocataireType;
use App\Repository\LocataireRepository;
use App\Repository\LogementRepository;
use App\Repository\ResidenceRepository;
use App\Repository\TodoRepository;
use App\Service\FileUploader;
use Mailjet\Resources;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

//TODO:; CREER NOUVELLE PARTIE 'SIGNATURE CONTRAT' - https://www..mgellogement.fr/GEST_V2/contrat_rdv.php?order=&tri=&id_residence=22
//TODO: VALIDATION DES DOCS DU DOSSIER LOC PAR MGEL LGT
//TODO: CONTRAT - ACTE DE CAUTION - ASSURANCE - PHOTO - DEPOT GARANTIE - SEPA - RIB
//TODO:CREATION D'UN BTN 'VALIDER LE DOSSIER' - LORS DU CLIQUE -> ENGAGEMENT EN ATTENTE OU EDLE A PRENDRE
//TODO: CREER SECTION 'ENGAGEMENT EN ATTENTE' https://www..mgellogement.fr/GEST_V2/locataire_en_attente.php


/**
 * @Route("/locataire")
 */
class LocataireController extends AbstractController
{
    /**
     * @Route("/{resid}",defaults={"resid"="De la Maine"}, name="locataire_index", methods={"GET"})
     */
    public function index(LocataireRepository $locataireRepository,ResidenceRepository $residenceRepository,$resid): Response
    {

//        TODO: LISTE PAR SAISONALITE
        $residence = $residenceRepository->findOneBy(['nom'=>$resid]);
        if($resid === 'tous'){
           $locataires = $locataireRepository->findAlls();
        } else {
            $locataires = $locataireRepository->findByResidence($residence);
        }
        return $this->render('gestionnaire/locataire/index.html.twig', [
            'locataires' => $locataires,
            'residenceChoisie' =>$residence,
            'residences' => $residenceRepository->findAll(),
        ]);
    }


    /**
     * @Route("/a/creation", name="locataire_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserPasswordEncoderInterface $encoder,FileUploader $fileUploader, \Swift_Mailer $mailer): Response
    {
        //TODO: POSSIBILITE DENVOYER LES DOCS (CF: TABLEAU EXCEL)
        $locataire = new Locataire();
        $garant = new Garant();
        $form = $this->createForm(LocataireType::class, $locataire);
        $formGarant = $this->createForm(GarantType::class,$garant);
        $form->handleRequest($request);
        $formGarant->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cni = $request->files->get('locataire')['documents']['cni'];
            $cni_garant = $request->files->get('garant')['documents']['cni'];
            $livret = $request->files->get('garant')['documents']['livret'];
            $justif_dom = $request->files->get('garant')['documents']['justif_dom'];
            $bull_sal = $request->files->get('garant')['documents']['bull_sal'];
            $avis_imp = $request->files->get('garant')['documents']['avis_imp'];
            $justif_bourse = $request->files->get('locataire')['documents']['justif_bourse'];

            if ($justif_bourse !== '') {
                $justif_bourse_name = $fileUploader->upload($justif_bourse);
                $locataire->setJustifBourse($justif_bourse_name);
            }
            $cni_name = $fileUploader->upload($cni);
            $cni_garant_name = $fileUploader->upload($cni_garant);
            $livret_name = $fileUploader->upload($livret);
            $justif_dom_name = $fileUploader->upload($justif_dom);
            $bull_sal_name = $fileUploader->upload($bull_sal);
            $avis_imp_name = $fileUploader->upload($avis_imp);

            $locataire->setCni($cni_name);
            $garant->setCni($cni_garant_name);
            $garant->setLivret($livret_name);
            $garant->setJustifDom($justif_dom_name);
            $garant->setBullSal($bull_sal_name);
            $garant->setAvisImp($avis_imp_name);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($garant);
            $locataire->setGarant($garant);
            $locataire->setPassword(
                $encoder->encodePassword($locataire,$locataire->getPlainPassword())
            );
            $locataire->setRecordBy($this->getUser());
            $locataire->setDateRecepDossier(new \DateTime('now'));
            $entityManager->persist($locataire);
            $entityManager->flush();
            // Create the message
            /*$message = (new \Swift_Message())

                // Give the message a subject
                ->setSubject('Your subject')

                // Set the From address with an associative array
                ->setFrom(['john@doe.com' => 'John Doe'])

                // Set the To addresses with an associative array (setTo/setCc/setBcc)
                ->setTo(['sam@bzez.co', 'other@domain.org' => 'A name'])

                // Give it a body
                ->setBody('Here is the message itself')

            ;
            $mailer->send($message);
            */
            $mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'),
                true,['version' => 'v3.1']);
            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => "logement@mgel.fr",
                            'Name' => "MGEL Logement"
                        ],
                        'To' => [
                            [
                                'Email' => $locataire->getEmail(),
                                'Name' => $locataire->getNom().' '.$locataire->getPrenom()
                            ]
                        ],
                        'Subject' => "Création de votre personnel MGEL Logement",
                        'HTMLPart' => "<h3>Bienvenue sur MGEL Logement</h3><br><h5>Votre mot de passe pour vous connectez à votre espace personnel est le suivant:</h5><br><h1>".$locataire->getPlainPassword()."</h1>"
                    ]
                ]
            ];
            $response = $mj->post(Resources::$Email, ['body' => $body]);
            $response->success();
//            return $this->redirectToRoute('locataire_index');
        }

        return $this->render('gestionnaire/locataire/new.html.twig', [
            'locataire' => $locataire,
            'form' => $form->createView(),
            'formGarant' => $formGarant->createView(),
        ]);
    }

    /**
     * @Route("/{resid}/{id}",defaults={"resid"="De la Maine"}, name="locataire_show", methods={"GET"})
     */
    public function show(LocataireRepository $locataireRepository,$id): Response
    {
//        TODO: BLOCAGE DU VOEU PAR MGEL LGT
//        TODO: SELECTION SIGN ELEC OU PAPIER (INTEGRER TOUT LES DOCS) // OPTION COLOC OU MEUBLE
        return $this->render('gestionnaire/locataire/show.html.twig', [
            'locataire' => $locataireRepository->findOne($id) ,
        ]);
    }

    /**
     * @Route("/{resid}/{id}/edit", name="locataire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,Locataire $locataire,UserPasswordEncoderInterface $encoder): Response
    {
        $old_password = $locataire->getPassword();
        $garant = $locataire->getGarant();
        $form = $this->createForm(LocataireType::class, $locataire);
        $form->remove('plainPassword');
        $formGarant = $this->createForm(GarantType::class, $garant);
        $form->handleRequest($request);
        $formGarant->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('locataire_index', [
                'id' => $locataire->getId(),
            ]);
        }

        return $this->render('gestionnaire/locataire/edit.html.twig', [
            'locataire' => $locataire,
            'form' => $form->createView(),
            'formGarant' => $formGarant->createView(),
        ]);
    }

    /**
     * @Route("/{resid}/{id}", name="locataire_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Locataire $locataire,LogementRepository $logementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$locataire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $logement = $logementRepository->findOneBy(['locataire'=>$locataire]);
            $logement->setOccupation(0);
            $entityManager->persist($logement);
            $entityManager->remove($locataire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('locataire_index');
    }

    /**
     * @Route("/{resid}/{id}/loger", name="locataire_set")
     */
    public function set(LogementRepository $logementRepository,TodoRepository $todoRepository,Locataire $locataire)
    {
        $em = $this->getDoctrine()->getManager();
        $todo = $todoRepository->findOneBy(['locataire'=>$locataire,'statut'=>'A loger']);
        $logement = $logementRepository->findOne($todo->getLogement());
        $logement->setOccupation(1);
        $logement->setDateLibre($todo->getDateSortie());
        $todo->setStatut('A liberer');
        $em->persist($todo);
        $em->persist($logement);
        $em->flush();

        return $this->redirectToRoute('home_gest');
    }
}
