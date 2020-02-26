<?php

namespace App\Controller\Gestionnaire;

use App\Entity\FaqTodo;
use App\Service\EcheanceUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class EcheanceController extends AbstractController
{
    /**
     * @Route("/_", name="echeance")
     */
    public function upload(Request $request,EcheanceUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted(['ROLE_ECHEANCE','ROLE_ADMIN','ROLE_ANIM'], null, 'Unable to access this page!');

       if($request->isMethod('POST'))
       {
           $file = $request->files->get('file');
           $fileUploader->upload($file);

       }
        return $this->render('gestionnaire/echeance/index.html.twig');
    }
}
