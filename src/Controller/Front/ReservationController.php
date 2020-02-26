<?php

namespace App\Controller\Front;

use App\Controller\MailingController;
use App\Entity\Prospect;
use App\Form\DemandeType;
use App\Form\ProspectType;
use App\Form\ResaType;
use App\Repository\LocataireRepository;
use App\Repository\LogementRepository;
use App\Repository\ProspectRepository;
use App\Repository\ResidenceRepository;
use App\Repository\VilleRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;
use Mailjet\Client;
use Mailjet\Resources;
use setasign\Fpdi\Fpdi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ReservationController extends AbstractController
{
    public function generatePassword()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz$';
// Output: 54esmdr0qf
        return substr(str_shuffle($permitted_chars), 0, 10);
    }


    /**
     * @Route("/demande-logement", name="demande-logement")
     */
    public function demande(Request $request, UserPasswordEncoderInterface $encoder, VilleRepository $villeRepository,MailingController $mailer)
    {
//        TODO: GENERER ET ENVOYER DL (CF: PDF LISA)
        $prospect = new Prospect();
        $form = $this->createForm(ResaType::class, $prospect);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $pwd = $this->generatePassword();
            $prospect->setPassword(
                $encoder->encodePassword($prospect, $pwd)
            );
            $mailer->mailProspect($prospect,$pwd);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prospect);

            /* $stats = new Stats();
             $now = new \DateTime('now');
             $stats->setAnnee($now->format('Y'));
             $stats->setMois($now->format('m'));
             $stats->setJour($now->format('d'));
             $stats->setEntite('Prospect');
             $stats->setResidence($prospect->getResidences()[0]);
             $entityManager->persist($stats);*/

            $entityManager->flush();
            $this->addFlash('notice', 'Votre demande de logement à bien été transmise !');
            return $this->redirectToRoute('accueil');
        }

        return $this->render('front/reservation/demande-logement.html.twig', [
            'form' => $form->createView(),
            'villes' => $villeRepository->findAll()
        ]);
    }

    /**
     * @Route("/check-mail", name="checkmail")
     */
    public function checkMail(LocataireRepository $locataireRepository, ProspectRepository $prospectRepository)
    {
        $email = $_POST['email'];
        if (!$locataireRepository->findOneBy(['email' => $email]) && !$prospectRepository->findOneBy(['email' => $email])) {
            //Email n'existe pas.
            return new Response("<i class='fas fa-thumbs-up'></i>&nbsp; Email valide !", 200);
        } else {
            //L'email existe déja !
            return new Response('', 400);
        }
    }
}
/* $residences = $residenceRepository->findAll();
        foreach ($residences as $residence) {
            $criteria = Criteria::create()->where(Criteria::expr()->eq('residence',$residence))->andWhere(Criteria::expr()->gt('annee',2018));
            $dispos[$residence->getNom()] = $this->getDoctrine()->getRepository(Disponibilite::class)->matching($criteria);
        }

         $villes = $villeRepository->findAll();
         $resa_residence = array();
         foreach ($villes as $ville) {
             $resa_residence[$ville->getNom()] = $residenceRepository->findBy(['ville' => $ville]);
         }

         $demande = new Demande();
         $form = $this->createForm(DemandeType::class, $demande);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {


             $em = $this->getDoctrine()->getManager();
             $em->persist($demande);
             $em->flush();

             return $this->redirect($this->generateUrl('admin'));
         }


         return $this->render('front/reservation/dispo-resa.html.twig', [
             'dispos' => $dispos,
             'residences' => $residences,
             'villes' =>$villes,
             'resa_resid' =>$resa_residence,
             'form' => $form->createView(),
         ]);
     }*/