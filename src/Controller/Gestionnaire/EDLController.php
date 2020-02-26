<?php

namespace App\Controller\Gestionnaire;

use App\Entity\EDL;
use App\Entity\Residence;
use App\Form\EDLType;
use App\Repository\EDLRepository;
use App\Repository\LocataireRepository;
use App\Repository\LogementRepository;
use App\Repository\ResidenceRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/etat-des-lieux")
 */
class EDLController extends AbstractController
{
    /**
     * @Route("/{resid}",defaults={"resid"="De la Maine"}, name="e_d_l_index", methods={"GET"})
     */
    public function index(EDLRepository $eDLRepository, $resid, ResidenceRepository $residenceRepository): Response
    {
        $residence = $residenceRepository->findOneBy(['nom' => $resid]);
        if ($resid === 'tous') {
            $edls = $eDLRepository->findAlls();
        } else {
            $edls = $eDLRepository->findByResidence($residence);
        }
        return $this->render('gestionnaire/edl/index.html.twig', [
            'e_d_ls' => $edls,
            'residenceChoisie' => $residence,
            'residences' => $residenceRepository->findAll()
        ]);
    }

    /**
     * @Route("/{resid}/rdv",defaults={"resid"="De la Maine"}, name="e_d_l_index_a_prendre", methods={"GET"})
     */
    public function indexAprendre(EDLRepository $eDLRepository, $resid, ResidenceRepository $residenceRepository,LocataireRepository $locataireRepository): Response
    {
        $residence = $residenceRepository->findOneBy(['nom' => $resid]);

        if ($resid === 'tous') {
            $locatairesEDLE = $locataireRepository->findAllWithoutEdle();
            $locatairesEDLS = $locataireRepository->findAllWithoutEdls();
        } else {
            $locatairesEDLE = $locataireRepository->findAllWithoutEdle($residence);
            $locatairesEDLS = $locataireRepository->findAllWithoutEdls($residence);
        }
        return $this->render('gestionnaire/edl/index-rdv.html.twig', [
            'locatairesEDLE' => $locatairesEDLE,
            'locatairesEDLS' => $locatairesEDLS,
            'residenceChoisie' => $residence,
            'residences' => $residenceRepository->findAll()
        ]);
    }

    /**
     * @Route("/{resid}/historique",defaults={"resid"="De la Maine"}, name="e_d_l_index_history", methods={"GET"})
     */
    public function indexHistory(EDLRepository $eDLRepository, $resid, ResidenceRepository $residenceRepository): Response
    {
        $residence = $residenceRepository->findOneBy(['nom' => $resid]);
        if ($resid === 'tous') {
            $edles = $eDLRepository->findEDLEHistory();
            $edlss = $eDLRepository->findEDLSHistory();
        } else {
            $edles = $eDLRepository->findEDLEHistory($residence);
            $edlss = $eDLRepository->findEDLSHistory($residence);
        }
        return $this->render('gestionnaire/edl/index-historique.html.twig', [
            'edles' => $edles,
            'edlss' => $edlss,
            'residenceChoisie' => $residence,
            'residences' => $residenceRepository->findAll()
        ]);
    }

    /**
     * @Route("/a/creation", name="e_d_l_new", methods={"GET","POST"})
     */
    public function new(Request $request, LogementRepository $logementRepository): Response
    {
        $eDL = new EDL();
        $form = $this->createForm(EDLType::class, $eDL);
        $form->handleRequest($request);
//        $form->remove('doc_manquant');
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $eDL->setLocataire($eDL->getLogement()->getLocataires()[0]);
            $entityManager->persist($eDL);
            $entityManager->flush();

            return $this->redirectToRoute('e_d_l_index');
        }
        if ($logId = $request->get('l')) {
            $logement = $logementRepository->findOne($logId);
        } else {
            $logement = null;
        }
        return $this->render('gestionnaire/edl/new.html.twig', [
            'e_d_l' => $eDL,
            'logement' => $logement,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{resid}/{id}", name="e_d_l_show", methods={"GET"})
     */
    public function show(EDL $eDL): Response
    {
        return $this->render('gestionnaire/edl/show.html.twig', [
            'e_d_l' => $eDL,
        ]);
    }

    /**
     * @Route("/accept/{resid}/{id}", name="e_d_l_accept")
     */
    public function accept(EDL $eDL): Response
    {
        $em = $this->getDoctrine()->getManager();
        $eDL->setStatut(1); //Validé
        $em->persist($eDL);
        $em->flush();
        return $this->redirectToRoute('e_d_l_show', [
            'e_d_l' => $eDL,
        ]);
    }

    /**
     * @Route("/{resid}/{id}/edit", name="e_d_l_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EDL $eDL): Response
    {
        $form = $this->createForm(EDLType::class, $eDL);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('e_d_l_show', [
                'id' => $eDL->getId(),
                'resid' => $eDL->getLogement()->getResidence()->getNom()
            ]);
        }

        return $this->render('gestionnaire/edl/edit.html.twig', [
            'e_d_l' => $eDL,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="e_d_l_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EDL $eDL): Response
    {
        if ($this->isCsrfTokenValid('delete' . $eDL->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($eDL);
            $entityManager->flush();
        }

        return $this->redirectToRoute('e_d_l_index');
    }
}
//TODO: AJOUTER VALID PAR ET DATE
/*
/**
 * @Route("/manquant/{resid}",defaults={"resid"="tous"}, name="e_d_l_index_manquant", methods={"GET"})
 */
/*public function indexManquant(EDLRepository $eDLRepository, $resid, ResidenceRepository $residenceRepository): Response
{
    $residence = $residenceRepository->findOneBy(['nom' => $resid]);
    if ($resid === 'tous') {
        $edls = $eDLRepository->findAllManquant();
    } else {
        $edls = $eDLRepository->findManquantByResidence($residence);
    }
    return $this->render('gestionnaire/edl/index.html.twig', [
        'e_d_ls' => $edls,
        'residenceChoisie' => $residence,
        'residences' => $residenceRepository->findAll(),
        'manquant' =>true
    ]);
}*/