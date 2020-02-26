<?php

namespace App\Controller\Gestionnaire;
//TODO: RENOUVELLEMENT https://www..mgellogement.fr/GEST_V2/contrat_renouvellement.php
//TODO: SI ENVOYER SUR LESPACE - https://www..mgellogement.fr/GEST_V2/contrat_reception.php?voir=0
// TODO: https://www..mgellogement.fr/GEST_V2/contrat_reception_detail.php?id_contrat=35028&id_locataire=27783&id_logement=1137&init=1
//TODO: SI VEOU = RESTE -> A ENVOYER
//TODO: CHAMPS DATE, DUREE, ETC POUR ASSISTANT GESTION OU PLUS
//TODO: PREVOIR SIGNATURE PHYSIQUE POUR RENOUVELLEMENT

//TODO: TRANSFERT YAY: GENERER EXCEL

//TODO: SI LOC VITAL ASSUR APPARAIT DANS https://www..mgellogement.fr/GEST_V2/trans_va.php // DOSSIER COMPLET OBLG

use App\Controller\MailingController;
use App\Entity\Contrat;
use App\Entity\Logement;
use App\Entity\Todo;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use App\Repository\ResidenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contrat")
 */
class ContratController extends AbstractController
{
    /**
     * @Route("/{resid}", defaults={"resid"="De la Maine"},name="contrat_index", methods={"GET"})
     */
    public function index(ContratRepository $contratRepository, $resid, ResidenceRepository $residenceRepository): Response
    {
        $residence = $residenceRepository->findOneBy(['nom' => $resid]);
        if ($resid === 'tous') {
            $contrats = $contratRepository->findAlls();
        } else {
            $contrats = $contratRepository->findAllUnSignedByResidence($residence);
        }
        $residence = $residenceRepository->findOneBy(['nom' => $resid]);
        return $this->render('gestionnaire/contrat/index.html.twig', [
            'contrats' => $contrats,
            'residenceChoisie' => $residence,
            'residences' => $residenceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/a/creation/{id}", name="contrat_new", methods={"GET","POST"})
     */
    public function new(Request $request, Logement $logement): Response
    {
        $contrat = new Contrat();
        $contrat->setLocataire($logement->getLocataire());
        $contrat->setLogement($logement);
        $contrat->setGenBy($this->getUser());
        $contrat->setDebut(new \DateTime($request->get('contrat')['date_debut']));
        $date_entree = $request->get('contrat')['date_debut'];
        $date_sortie = strtotime(date("Y-m-d", strtotime($date_entree)) . " +365 day");
        $date_sortie = date('Y-m-d', $date_sortie);
        $contrat->setFin(new \DateTime($date_sortie));
        $contrat->setDuree(12);
        $em = $this->getDoctrine()->getManager();
        $em->persist($contrat);

        $now = new \DateTime('now');

        if ($contrat->getDebut() === $now->format('Y-m-d')) {
            $logement->setOccupation(1);
            $logement->setDateLibre($contrat->getFin());
            $em->persist($logement);
        }
        $todo = new Todo();
        $todo->setLocataire($logement->getLocataire());
        $todo->setLogement($logement);
        $todo->setDateEntree($contrat->getDebut());
        $todo->setDateSortie($contrat->getFin());
        $em->persist($todo);

        $em->flush();
        $contrat->setPdf($logement->getNumero() . "_" . $logement->getLocataire()->getId() . "_" . $contrat->getId() . ".pdf");
        $em->persist($contrat);
        $em->flush();

        return $this->redirectToRoute($request->get('route'), ['id' => $request->get('idLoc')]);
    }

    /**
     * @Route("/{id}", name="contrat_show", methods={"GET"})
     */
    public function show(Contrat $contrat): Response
    {
        return $this->render('gestionnaire/contrat/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contrat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contrat $contrat): Response
    {
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contrat_index', [
                'id' => $contrat->getId(),
            ]);
        }

        return $this->render('gestionnaire/contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contrat_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Contrat $contrat): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contrat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contrat_index');
    }

    /**
     * @Route("/set/rdv", name="contrat_set_rdv",methods={"POST"})
     */
    public function setRdv(MailingController $mailer,ContratRepository $contratRepository)
    {
        $contrat = $contratRepository->find($_POST['idContrat']);
        $em = $this->getDoctrine()->getManager();
        $user=$contrat->getLocataire();
        $date = new \DateTime($_POST['dateSign'].' '.$_POST['heureSign'].':00');
        $contrat->setDateSignature($date);
        $em->persist($contrat);
        $em->flush();
        $mailer->mailRdvSignature($user);
        $this->addFlash('success','Le RDV de signature de contrat à bien été enregistré et transmis !');
        return $this->redirectToRoute('contrat_index');
    }

    /**
     * @Route("/mail/rdv/{id}", name="contrat_mail_rdv")
     */
    public function mailRdv(Contrat $contrat, MailingController $mailer)
    {
        $user = $contrat->getLocataire();
        $mailer->mailRdvSignature($user);
        return $this->redirectToRoute('contrat_index');
    }
}
