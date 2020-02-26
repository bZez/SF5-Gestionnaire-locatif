<?php

namespace App\Controller\Gestionnaire;

use App\Entity\Contrat;
use App\Repository\LocataireRepository;
use App\Repository\LogementRepository;
use App\Service\MyTransactionDocument;
use Dompdf\Dompdf;
use Dompdf\Options;
use Globalis\Universign\Request\DocSignatureField;
use Globalis\Universign\Request\TransactionDocument;
use Globalis\Universign\Request\TransactionRequest;
use Globalis\Universign\Request\TransactionSigner;
use Globalis\Universign\Requester;
use Globalis\Universign\Response\TransactionInfo;
use Jurosh\PDFMerge\PDFMerger;
use PhpXmlRpc\Client;
use PhpXmlRpc\PhpXmlRpc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("doc")
 */
class PdfController extends AbstractController
{
    /**
     * @Route("/contrat/{loc}", name="gen_contrat")
     */
    public function index($loc, LocataireRepository $locataireRepository)
    {
        $locataire = $locataireRepository->find($loc);


        /*$this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_ANIM', 'ROLE_LOCATAIRE']);

       if(!$this->isGranted('ROLE_ADMIN')){
           if ($this->getUser()->getId() !== $locataire->getId()) {
               return $this->redirectToRoute('home_loc');
           }
       }*/

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('gestionnaire/pdf/contrat-vierge.html.twig', [
            'locataire' => $locataire,
            'logement' => $locataire->getLogement(),
            'garant' => $locataire->getGarant(),
            'residence' => $locataire->getLogement()->getResidence(),
            'contrat' => $locataire->getLastContrat()
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * @Route("/sepa/{loc}", name="gen_sepa")
     */
    public function sepa($loc, LocataireRepository $locataireRepository)
    {
        $locataire = $locataireRepository->find($loc);


        /* $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_ANIM', 'ROLE_LOCATAIRE']);

         if(!$this->isGranted('ROLE_ADMIN')){
             if ($this->getUser()->getId() !== $locataire->getId()) {
                 return $this->redirectToRoute('home_loc');
             }
         }*/

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Calibri');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('gestionnaire/pdf/sepa-vierge.html.twig', [
            'logo' => $this->getParameter('kernel.project_dir') . '\public\assets\img\mgellogement.png',
            'locataire' => $locataire,
            'logement' => $locataire->getLogement(),
            'garant' => $locataire->getGarant(),
            'residence' => $locataire->getLogement()->getResidence(),
            'contrat' => $locataire->getLastContrat()
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * @Route("/caution/{loc}", name="gen_caution")
     */
    public function caution($loc, LocataireRepository $locataireRepository)
    {
        $locataire = $locataireRepository->find($loc);


        /*      $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_ANIM', 'ROLE_LOCATAIRE']);

              if(!$this->isGranted('ROLE_ADMIN')){
                  if ($this->getUser()->getId() !== $locataire->getId()) {
                      return $this->redirectToRoute('home_loc');
                  }
              }*/

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Calibri');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('gestionnaire/pdf/caution-vierge.html.twig', [
            'logo' => $this->getParameter('kernel.project_dir') . '\public\assets\img\mgellogement.png',
            'locataire' => $locataire,
            'logement' => $locataire->getLogement(),
            'garant' => $locataire->getGarant(),
            'residence' => $locataire->getLogement()->getResidence(),
            'contrat' => $locataire->getLastContrat()
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf", [
            "Attachment" => false
        ]);
    }


    /**
     * @Route("/dossier/{loc}", name="gen_dossier")
     */
    public function complet(LocataireRepository $locataireRepository, $loc)
    {
        $locataire = $locataireRepository->find($loc);
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial Black');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('gestionnaire/pdf/dossier-complet.html.twig', [
            'logo' => $this->getParameter('kernel.project_dir') . '\public\assets\img\mgellogement.png',
            'rib' => $this->getParameter('kernel.project_dir') . '\public/datas/' . $locataire->getRib(),
            'locataire' => $locataire,
            'logement' => $locataire->getLogement(),
            'garant' => $locataire->getGarant(),
            'residence' => $locataire->getLogement()->getResidence(),
            'contrat' => $locataire->getLastContrat()
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * @Route("/annexe/{num}/{loc}", name="gen_annexe")
     */
    public function annexe1(LocataireRepository $locataireRepository, $loc, $num)
    {
        $locataire = $locataireRepository->find($loc);
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial Black');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('gestionnaire/pdf/annexe-' . $num . '.html.twig', [
            'logo' => $this->getParameter('kernel.project_dir') . '\public\assets\img\mgellogement.png',
            'rib' => $this->getParameter('kernel.project_dir') . '\public/datas/' . $locataire->getRib(),
            'locataire' => $locataire,
            'logement' => $locataire->getLogement(),
            'garant' => $locataire->getGarant(),
            'residence' => $locataire->getLogement()->getResidence(),
            'contrat' => $locataire->getLastContrat()
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * @Route("/signature",name="signature_contrat",host="locataire.mgellogement.fr")
     */
    public function signer()
    {
        $this->denyAccessUnlessGranted('ROLE_LOCATAIRE');
        $locataire = $this->getUser();
        $contrat = $locataire->getLastContrat();
        if ($contrat->getSignature()) {
            return $this->redirectToRoute('sign_success', ['id' => $contrat->getId()]);
        }

        $loc = new TransactionSigner();
        $loc
            ->setFirstname($locataire->getPrenom())
            ->setLastname($locataire->getNom())
            ->setPhoneNum($locataire->getTelMobile())
            ->setEmailAddress($locataire->getEmail())
            ->setSuccessURL('http://locataire.mgellogement.fr/doc/sign/success/' . $contrat->getId())
            ->setCancelURL('https://www.universign.eu/fr/sign/cancel/')
            ->setFailURL('https://www.universign.eu/fr/sign/failed/');

        $gar = new TransactionSigner();
        $gar
            ->setFirstname($locataire->getPrenom())
            ->setLastname($locataire->getNom())
            ->setPhoneNum($locataire->getTelMobile())
            ->setEmailAddress($locataire->getEmail())
            ->setSuccessURL('http://locataire.mgellogement.fr/doc/sign/success/' . $contrat->getId())
            ->setCancelURL('https://www.universign.eu/fr/sign/cancel/')
            ->setFailURL('https://www.universign.eu/fr/sign/failed/');

        $trequest = new TransactionRequest();
        $signatureLocContrat = new DocSignatureField();
        $signatureGarContrat = new DocSignatureField();
        $signatureLocAnnexe1 = new DocSignatureField();
        $signatureGarAnnexe1 = new DocSignatureField();
        $signatureLocAnnexe2 = new DocSignatureField();
        $signatureGarAnnexe2 = new DocSignatureField();
        $signatureLocAnnexe3 = new DocSignatureField();
        $signatureGarAnnexe3 = new DocSignatureField();
        $signatureLocAnnexeColoc = new DocSignatureField();
        $signatureGarAnnexeColoc = new DocSignatureField();
        $signatureGarCaution = new DocSignatureField();
        $signatureLocSepa = new DocSignatureField();
        $signatureGarSepa = new DocSignatureField();

        //       CONTRAT
        $signatureLocContrat->setPage(1)
            ->setX(40)
            ->setY(10)
            ->setSignerIndex(0);
        $signatureGarContrat->setPage(1)
            ->setX(260)
            ->setY(10)
            ->setSignerIndex(1);

        //        ANNEXE 1
        $signatureLocAnnexe1->setPage(1)
            ->setX(40)
            ->setY(10)
            ->setSignerIndex(0);
        $signatureGarAnnexe1->setPage(1)
            ->setX(260)
            ->setY(10)
            ->setSignerIndex(1);

        //        ANNEXE 2
        $signatureLocAnnexe2->setPage(3)
            ->setX(40)
            ->setY(50)
            ->setSignerIndex(0);
        $signatureGarAnnexe2->setPage(3)
            ->setX(300)
            ->setY(50)
            ->setSignerIndex(1);

        //         ANNEXE 3
        $signatureLocAnnexe3->setPage(1)
            ->setX(40)
            ->setY(50)
            ->setSignerIndex(0);
        $signatureGarAnnexe3->setPage(1)
            ->setX(300)
            ->setY(50)
            ->setSignerIndex(1);

        //         ANNEXE COLOC
        $signatureLocAnnexeColoc->setPage(1)
            ->setX(40)
            ->setY(50)
            ->setSignerIndex(0);
        $signatureGarAnnexeColoc->setPage(1)
            ->setX(300)
            ->setY(50)
            ->setSignerIndex(1);

        //         CAUTION
        $signatureGarCaution->setPage(1)
            ->setX(300)
            ->setY(50)
            ->setSignerIndex(1);

        //         SEPA
        $signatureLocSepa->setPage(1)
            ->setX(40)
            ->setY(10)
            ->setSignerIndex(0);
        $signatureGarSepa->setPage(1)
            ->setX(300)
            ->setY(10)
            ->setSignerIndex(1);

//dump(file_get_contents('http://gest.mgellogement.fr/pdf/contrat/4'));die;
//        $doc = new TransactionDocument();

        $documentContrat = new MyTransactionDocument();
        $documentContrat->setPath('CONTRAT_' . $locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf");
        $documentContrat->setContent(file_get_contents('http://locataire.mgellogement.fr/doc/contrat/' . $locataire->getId()));
        $documentContrat->setSignatureFields([$signatureLocContrat, $signatureGarContrat]);

        $documentAnnexe1 = new MyTransactionDocument();
        $documentAnnexe1->setPath('ANNEXE_1_' . $locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf");
        $documentAnnexe1->setContent(file_get_contents('http://locataire.mgellogement.fr/doc/annexe/1/' . $locataire->getId()));
        $documentAnnexe1->setSignatureFields([$signatureLocAnnexe1, $signatureGarAnnexe1]);

        $documentAnnexe2 = new MyTransactionDocument();
        $documentAnnexe2->setPath('ANNEXE_2_' . $locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf");
        $documentAnnexe2->setContent(file_get_contents('http://locataire.mgellogement.fr/doc/annexe/2/' . $locataire->getId()));
        $documentAnnexe2->setSignatureFields([$signatureLocAnnexe2, $signatureGarAnnexe2]);

        if ($locataire->getResidence()->getId() == 2 || $locataire->getResidence()->getId() == 4 || $locataire->getResidence()->getId() == 23 || $locataire->getResidence()->getId() == 26 || $locataire->getResidence()->getId() == 27) {
            $documentAnnexe3 = new MyTransactionDocument();
            $documentAnnexe3->setPath('ANNEXE_3_' . $locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf");
            $documentAnnexe3->setContent(file_get_contents('http://locataire.mgellogement.fr/doc/annexe/3/' . $locataire->getId()));
            $documentAnnexe3->setSignatureFields([$signatureLocAnnexe3, $signatureGarAnnexe3]);
        }

        if ($locataire->getResidence()->getId() == 19 || $locataire->getResidence()->getId() == 24 || $locataire->getResidence()->getId() == 28 || $locataire->getResidence()->getId() == 23 || $locataire->getResidence()->getId() == 27) {
            if ($locataire->getColocation()) {
                $documentAnnexeColoc = new MyTransactionDocument();
                $documentAnnexeColoc->setPath('ANNEXE_COLOC_' . $locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf");
                $documentAnnexeColoc->setContent(file_get_contents('http://locataire.mgellogement.fr/doc/annexe/coloc/' . $locataire->getId()));
                $documentAnnexeColoc->setSignatureFields([$signatureLocAnnexeColoc, $signatureGarAnnexeColoc]);

            }
        }

        $documentCaution = new MyTransactionDocument();
        $documentCaution->setPath('CAUTION_' . $locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf");
        $documentCaution->setContent(file_get_contents('http://locataire.mgellogement.fr/doc/caution/' . $locataire->getId()));
        $documentCaution->setSignatureFields([$signatureGarCaution]);

        $documentSepa = new MyTransactionDocument();
        $documentSepa->setPath('SEPA_' . $locataire->getLogement()->getId() . "_" . $locataire->getId() . "_" . $locataire->getLastContrat()->getId() . ".pdf");
        $documentSepa->setContent(file_get_contents('http://locataire.mgellogement.fr/doc/sepa/' . $locataire->getId()));
        $documentSepa->setSignatureFields([$signatureLocSepa, $signatureGarSepa]);
        /*$doc->setPath($this->getParameter('kernel.project_dir'). '/public/_datas_/_contrats_/'.$locataire->getLogement()->getNumero()."_".$locataire->getId()."_".$locataire->getLastContrat()->getId().".pdf")
            ->setSignatureFields([$signatureField,$signatureField2]);*/

        $trequest->addDocument($documentContrat)
            ->addDocument($documentAnnexe1)
            ->addDocument($documentAnnexe2);
        if ($locataire->getResidence()->getId() == 2 || $locataire->getResidence()->getId() == 4 || $locataire->getResidence()->getId() == 23 || $locataire->getResidence()->getId() == 26 || $locataire->getResidence()->getId() == 27) {
            $trequest->addDocument($documentAnnexe3);
        }
        if ($locataire->getResidence()->getId() == 19 || $locataire->getResidence()->getId() == 24 || $locataire->getResidence()->getId() == 28 || $locataire->getResidence()->getId() == 23 || $locataire->getResidence()->getId() == 27) {
            if ($locataire->getColocation()) {
                $trequest->addDocument($documentAnnexeColoc);
            }
        }
        $trequest->addDocument($documentCaution);
        $trequest->addDocument($documentSepa)
            ->setSigners([$loc, $gar])
            ->setHandwrittenSignatureMode(
                \Globalis\Universign\Request\TransactionRequest::HANDWRITTEN_SIGNATURE_MODE_BASIC)
            ->setChainingMode(
                \Globalis\Universign\Request\TransactionRequest::CHAINING_MODE_WEB
            )
            ->setMustContactFirstSigner(true)
            ->setFinalDocRequesterSent(true)
            ->setDescription("Signature du contrat de mutuelle")
            ->setLanguage('fr');


// Create XmlRpc Client
        $client = new \PhpXmlRpc\Client('https://sign.test.cryptolog.com/sign/rpc/');

        $client->setCredentials(
            'soukaina.laarissi@mgel.fr',
            'Mgel41bis'
        );

        $req = new Requester($client);

// Return a \Globalis\Universign\Response\TransactionResponse (with transaction url and id)
        $response = $req->requestTransaction($trequest);

        $signatureId = substr($response->url, strrpos($response->url, 'id=') + 3);
        $transactionId = $response->id;

        $em = $this->getDoctrine()->getManager();
        $contrat->setUniversignSignId($signatureId);
        $contrat->setUniversignTransId($transactionId);
        $em->persist($contrat);
        $em->flush();

        return $this->render('gestionnaire/pdf/signature.html.twig', [
            'signerId' => $signatureId
        ]);
    }

    /**
     * @Route("/sign/success/{id}",name="sign_success")
     */
    public function successSign(Contrat $contrat)
    {
       if(!$contrat->getSignature())
       {
           $contrat->setSignature(true);
           $contrat->setDateSignature(new \DateTime('now'));
           $em = $this->getDoctrine()->getManager();
           $em->persist($contrat);
           $em->flush();
       }
        $client = new Client('https://sign.test.cryptolog.com/sign/rpc/');

        $client->setCredentials(
            'soukaina.laarissi@mgel.fr',
            'Mgel41bis'
        );

        $req = new Requester($client);
        $transactionId = $contrat->getUniversignTransId();
        $response = $req->getTransactionInfo($transactionId);
        $pdf = new PDFMerger();
        if(!is_dir($this->getParameter('kernel.project_dir') . '/public/datas/_contrats_/'.$contrat->getLocataire()->getId())) {
            mkdir($this->getParameter('kernel.project_dir') . '/public/datas/_contrats_/' . $contrat->getLocataire()->getId());
        }
        if ($response->status === TransactionInfo::STATUS_COMPLETED) {
            $docs = $req->getDocuments($transactionId);
            foreach ($docs as $doc) {
                // Doc content
                $doc->content;
                // Doc file_name
                $file = fopen($this->getParameter('kernel.project_dir') . '/public/datas/_contrats_/'.$contrat->getLocataire()->getId().'/signe_' . $doc->name, 'w');
                fputs($file, $doc->content);
                fclose($file);
                $pdf->addPDF($this->getParameter('kernel.project_dir') . '/public/datas/_contrats_/'.$contrat->getLocataire()->getId().'/signe_' . $doc->name);
            }
        }
        $pdf->merge('browser',$this->getParameter('kernel.project_dir') . '/public/datas/_contrats_/DOSSIER_COMPLET_'. $contrat->getLocataire()->getLogement()->getId() . "_" . $contrat->getLocataire()->getId() . "_" . $contrat->getLocataire()->getLastContrat()->getId() .'.pdf');
        /*header("Content-type:application/pdf");

// It will be called downloaded.pdf
        header("Content-Disposition:attachment;filename=Contrat_Signe_" . $doc->name . "");

// The PDF source is in original.pdf
        readfile($this->getParameter('kernel.project_dir') . '/public/datas/_contrats_/signe_' . $doc->name);*/

    }
}
