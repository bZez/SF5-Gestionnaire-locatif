<?php

namespace App\Controller\Front;

use App\Controller\MailingController;
use App\Entity\CourtSejour;
use App\Form\CourtSejourType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CourtSejourController extends AbstractController
{
    /**
     * @Route("/courts-sejours", name="courts-sejours")
     */
    public function courtSejour(Request $request,MailingController $mailer)
    {
        $prospectCS = new CourtSejour();
        $form = $this->createForm(CourtSejourType::class, $prospectCS);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($prospectCS);
            $em->flush();
            $mailer->mailCourtSejour($prospectCS);
            $this->addFlash('notice', 'Votre demande de court séjour à bien été transmis !');
            return $this->redirectToRoute('accueil');
        }
        return $this->render('front/reservation/nos-disponibilites.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
