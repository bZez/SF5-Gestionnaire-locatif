<?php

namespace App\Controller\Front;

//TODO: CREATION TROMBI - TRAVAUX
// TODO: PHOTO DANS FICHE LOC
use App\Entity\Garant;
use App\Entity\Locataire;
use App\Entity\Logement;
use App\Entity\Mailing;
use App\Entity\Newsletter;
use App\Entity\Tuteur;
use App\Entity\TypeLogementTarif;
use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Repository\FaqRepository;
use App\Repository\GarantRepository;
use App\Repository\LocataireRepository;
use App\Repository\ResidenceRepository;
use App\Repository\TuteurRepository;
use App\Repository\UserRepository;
use Mailjet\Resources;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/",host="")
 */
class GlobalController extends AbstractController
{
    /**
     * @Route("/test")
     */
    public function testt()
    {
       return  $this->render('front/test.html.twig');
    }


    /**
     * @Route("/nos-residences", name="nos-residences")
     */
    public function index(ResidenceRepository $residenceRepository)
    {
        $residences = $residenceRepository->findAll();
        return $this->render('front/index.html.twig', [
            'residences' => $residences,
        ]);

    }

    /**
     * @Route("/qui-sommes-nous", name="qui-sommes-nous")
     */
    public function quiSommesNous()
    {

        return $this->render('front/qui-sommes-nous.html.twig');

    }


    /**
     * @Route("/decouvrez-mgel-logement", name="decouvrez-mgel")
     */
    public function decouvrezMgel()
    {

        return $this->render('front/decouvrez-mgel-logement.html.twig');

    }

    /**
     * @Route("/nos-services", name="nos-services")
     */
    public function nosServices()
    {

        return $this->render('front/nos-services.html.twig');

    }

    /**
     * @Route("/notre-concept", name="notre-concept")
     */
    public function notreConcept()
    {

        return $this->render('front/notre-concept.html.twig');

    }

    /**
     * @Route("/reserver-logement", name="reservation")
     */
    public function reserverLogement()
    {
        return $this->render('front/reservation/index.html.twig');
    }

    /**
     * @Route("/nous-contacter", name="contact")
     */
    public function contact(Request $request)
    {
        /*// 1) build the form
        $contact = new Mailing();
        $form = $this->createForm(ContactType::class, $contact);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        $userIp = $request->getClientIp();
        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setIp($userIp);
            $message = (new \Swift_Message($contact->getSujet()))
                ->setFrom($contact->getEmail())
                ->setTo('samir.moussaid@mgel.fr')
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig',
                        [
                            'nom' => $contact->getNom(),
                            'prenom' => $contact->getPrenom(),
                            'email' => $contact->getEmail(),
                            'tel' => $contact->getTelephone(),
                            'sujet' => $contact->getSujet(),
                            'message' => $contact->getMessage()
                        ]
                    ),
                    'text/html'
                );
            $mailer->send($message);
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            $this->addFlash(
                'notice',
                'Votre message a bien été envoyé !'
            );
            return $this->redirectToRoute('contact');
        }
        return $this->render('front/contact.html.twig', [
            'form' => $form->createView(),
            'userIp' => $userIp,
        ]);*/

    }

    /**
     * @Route("/newsletter", name="newsletter")
     */
    public function newsletter(Request $request)
    {
        $apikey = getenv('MJ_APIKEY_PUBLIC');
        $apisecret = getenv('MJ_APIKEY_PRIVATE');
        $id = 1340;

        $mj = new \Mailjet\Client($apikey, $apisecret);
        $body = [
            'Action' => "addforce",
            'Contacts' => [
                [
                    'Email' => $request->get('email'),
                ]
            ]
        ];
        $mj->post(Resources::$ContactslistManagemanycontacts, ['id' => $id, 'body' => $body]);
        return new JsonResponse(array(
            'status' => 'OK',
            'message' => 'Inscrit à la Newsletter'),
            200);
    }

    /**
     * @Route("/gen")
     */
    public function gen(ResidenceRepository $residenceRepository)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($residenceRepository->findAlls() as $residence)
        {
            $alphas = range('A', 'Z');
            foreach ($alphas as $alpha)
            {
                $type = new TypeLogementTarif();
                $type->setCategorie($alpha);
                $type->setResidence($residence);
                $type->setLoyer('161.00');
                $type->setCharges('120.00');
                $type->setCotisAcc('121.00');
                $type->setCotisServices('40.00');
                $em->persist($type);
            }
        }
        $em->flush();
        return new Response('Tout est ok !');
    }
}
