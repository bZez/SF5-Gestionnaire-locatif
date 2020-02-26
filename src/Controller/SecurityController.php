<?php

namespace App\Controller;

use App\Repository\LocataireRepository;
use App\Repository\ProspectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/oublie-pass", name="gen_pass",host="locataire.mgellogement.fr")
     */
    public function newPass(Request $request, LocataireRepository $locataireRepository, ProspectRepository $prospectRepository,MailingController $mailer,UserPasswordEncoderInterface $encoder)
    {
        $mail = $request->get('email');
        $zip = $request->get('zipCode');
        $em = $this->getDoctrine()->getManager();
        if ($locataire = $locataireRepository->findOneBy(['email' => $mail, 'code_postal' => $zip])) {
            $pwd = $this->generatePassword();
            $locataire->setPassword($encoder->encodePassword($locataire,$pwd));
            $mailer->mailPassword($locataire,$pwd);
            $em->persist($locataire);
            $em->flush();
        } elseif ($prospect = $prospectRepository->findOneBy(['email' => $mail, 'code_postal' => $zip])) {
            $pwd = $this->generatePassword();
            $prospect->setPassword($encoder->encodePassword($prospect,$pwd));
            $mailer->mailPassword($prospect,$pwd);
            $em->persist($prospect);
            $em->flush();
        }
        $this->addFlash('success','Votre demande de réinitialisaiton de mot de passe à bien été prise en compte !');
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
    public function generatePassword()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz$';
// Output: 54esmdr0qf
        return substr(str_shuffle($permitted_chars), 0, 10);
    }

}
