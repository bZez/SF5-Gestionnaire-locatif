<?php

namespace App\Controller\Gestionnaire;

use App\Entity\TypeLogementTarif;
use App\Form\TypeLogementTarifType;
use App\Repository\ResidenceRepository;
use App\Repository\TypeLogementTarifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/logement-tarif")
 */
class TypeLogementTarifController extends AbstractController
{
    /**
     * @Route("/{resid}",defaults={"resid"="De la Maine"}, name="type_logement_tarif_index", methods={"GET"})
     */
    public function index(TypeLogementTarifRepository $typeLogementTarifRepository, ResidenceRepository $residenceRepository, $resid): Response
    {
        $residence = $residenceRepository->findOneBy(['nom' => $resid]);
        if ($resid === 'tous') {
            $type_logement_tarifs = $typeLogementTarifRepository->findAll();
        } else {
            $type_logement_tarifs = $typeLogementTarifRepository->findByResidence($residence);
        }
        return $this->render('gestionnaire/type_logement_tarif/index.html.twig', [
            'type_logement_tarifs' => $type_logement_tarifs,
            'residences' => $residenceRepository->findAll(),
            'residenceChoisie' => $residence
        ]);
    }

    /**
     * @Route("/{resid}",defaults={"resid"="De la Maine"}, name="type_logement_tarif_update", methods={"POST"})
     */
    public function updateTarif(TypeLogementTarifRepository $typeLogementTarifRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($_POST['tarif'] as $tarif)
        {
            $type = $typeLogementTarifRepository->find($tarif['id']);
            $type->setLoyer($tarif['loyer']);
            $type->setCharges($tarif['charges']);
            $type->setCotisAcc($tarif['cotisAcc']);
            $type->setCotisServices($tarif['cotisServices']);
            $em->persist($type);
        }
        $em->flush();
        $residence = $type->getResidence();
        return $this->redirectToRoute('type_logement_tarif_index',['resid'=>$residence->getNom()]);
    }
}
