<?php

namespace App\Controller\Front;

use App\Entity\Logement;
use App\Repository\ResidenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class SwitcherController extends AbstractController
{

    /**
     * @Route("/", name="site",host="51.83.98.191")
     */
    public function index()
    {
       return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/", name="home_gest",host="gest.mgellogement.fr")
     */
    public function gestionSwitch()
    {
        if($this->isGranted(['ROLE_ADMIN','ROLE_ANIM']))
        {
            return $this->redirectToRoute('dashboard_index');
        }
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/", name="home_loc",host="locataire.mgellogement.fr")
     */
    public function locataireSwitch()
    {
        if($this->isGranted(['ROLE_LOCATAIRE','ROLE_PROSPECT']))
        {
            return $this->redirectToRoute('locataire_home');
        }
        return $this->redirectToRoute('app_login');
    }
}
