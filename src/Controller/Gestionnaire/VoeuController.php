<?php

namespace App\Controller\Gestionnaire;

use App\Entity\Voeu;
use App\Form\VoeuType;
use App\Repository\LocataireRepository;
use App\Repository\LogementRepository;
use App\Repository\ResidenceRepository;
use App\Repository\VoeuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/voeux")
 */
class VoeuController extends AbstractController
{
    /**
     * @Route("/{residenceNom}",defaults={"residenceNom"="De la Maine"}, name="voeu_index", methods={"GET","POST"})
     */
    public function index($residenceNom,VoeuRepository $voeuRepository,LogementRepository $logementRepository,ResidenceRepository $residenceRepository): Response
    {
//        TODO: SI VOEU = PART -> LOGEMENT DISPONIBLE DANS LISTE NOUVELLE LOCATION
        $residence = $residenceRepository->findOneBy(['nom'=>$residenceNom]);
        $logements = $logementRepository->findByResidence($residence);
        return $this->render('gestionnaire/voeu/index.html.twig', [
            'logements' =>$logements,
            'residenceChoisie' => $residence,
            'residences' => $residenceRepository->findAll()
        ]);
    }

    /**
     * @Route("/a/creation", name="voeu_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $voeu = new Voeu();
        $form = $this->createForm(VoeuType::class, $voeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($voeu);
            $entityManager->flush();

            return $this->redirectToRoute('voeu_index');
        }

        return $this->render('gestionnaire/voeu/new.html.twig', [
            'voeu' => $voeu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="voeu_show", methods={"GET"})
     */
    public function show(Voeu $voeu): Response
    {
        return $this->render('gestionnaire/voeu/show.html.twig', [
            'voeu' => $voeu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="voeu_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Voeu $voeu): Response
    {
        $form = $this->createForm(VoeuType::class, $voeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('voeu_index', [
                'id' => $voeu->getId(),
            ]);
        }

        return $this->render('gestionnaire/voeu/edit.html.twig', [
            'voeu' => $voeu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="voeu_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Voeu $voeu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voeu->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($voeu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('voeu_index');
    }

    /**
     * @Route("/reset",name="voeu_reset")
     */
    public function reset(LogementRepository $logementRepository,VoeuRepository $voeuRepository)
    {
        $logements = $logementRepository->findAll();
        foreach ($logements as $logement)
        {
            $voeu = new Voeu();
            $id = $logement->getId();


        }
    }
}
