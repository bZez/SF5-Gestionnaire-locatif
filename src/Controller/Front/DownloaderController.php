<?php

namespace App\Controller\Front;

use App\Entity\Locataire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DownloaderController extends AbstractController
{
    /**
     * @Route("/echeance/{id}/{annee}/{mois}/{file}", name="downloader")
     */
    public function index($id,$annee,$mois,$file)
    {
        $base = $this->getParameter('echeances_dir');
        return  new BinaryFileResponse($base.'/'.$id.'/'.$annee.'/'.$mois.'/'.$file.'.pdf');
    }

    /**
     * @Route("/echeance/{id}/{annee}/{mois}/parking/{file}", name="downloader_parking")
     */
    public function indexP($id,$annee,$mois,$file)
    {
        $base = $this->getParameter('echeances_dir');
        return  new BinaryFileResponse($base.'/'.$id.'/'.$annee.'/'.$mois.'/parking/'.$file.'.pdf');
    }
}
