<?php

namespace App\Controller;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MailingController extends AbstractController
{
    private $mj;

    public function __construct()
    {
        $this->mj = new Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'),
            true, ['version' => 'v3.1']);
    }

    public function mailProspect($prospect, $pwd = null)
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "contact@mgellogement.fr",
                        'Name' => "MGEL Logement"
                    ],
                    'To' => [
                        [
                            'Email' => $prospect->getEmail(),
                            'Name' => $prospect->getNom() . ' ' . $prospect->getPrenom()
                        ]
                    ],
                    'Subject' => "Confirmation de réception de votre demande de logement",
                    'HTMLPart' => "<p style='text-align: justify'>Bonjour" . $prospect->getPrenom() . ",<br><br>
Suite à votre demande de logement effectué sur notre site internet, nous vous prions de trouver ci-joint votre formulaire de demande de logement. 
<br><br>
Sachez toutefois, que vous pouvez deposer directement vos docuements sur notre site internet via votre espace personnel.
<br><br>
Une fois votre demande compléte, nous vous informerons de la recevabilité de celle-ci, et nous vous proposerons un logement en fonction de nos disponibilités.
<br><br>
Pour toutes questions n’hesitez pas à utiliser notre rubrique « Aide » sur notre site ou à nous contacter directement au <a href='tel:03 83 54 32 67'>03 83 54 32 67</a></p>. <hr>
Votre mot de passe pour vous connecter à votre espace personnel est le suivant:<br><h1>" . $pwd . "</h1>"
                ]
            ]
        ];
        $response = $this->mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }

    public function mailRelanceDoc($prospect)
    {
        $docsLoc = array();
        $docsGarant = array();
        if (!$prospect->getCni())
            array_push($docsLoc, "Carte d’identité recto-verso");
        if (!$prospect->getPhoto())
            array_push($docsLoc, "Photo d'identité");
        if (!$prospect->getJustifBourse())
            array_push($docsLoc, "Justificatif de bourse (si boursier)");
        if (!$prospect->getRib())
            array_push($docsLoc, "Autorisation de prévlèvement (SEPA)");
        if (!$prospect->getSepa())
            array_push($docsLoc, "Relevé d'identité bancaire");
        if (!$prospect->getChequeFrais())
            array_push($docsLoc, "Chèque de frais d'un montant de 150€ à l'ordre de UES MGEL Logement");
        if ($prospect->getStatutPro() == false) //Etudiant
        {
            if (!$prospect->getJustifScol())
                array_push($docsLoc, "Justificatif de scolarité");
        } else { //Jeune actif
            if (!$prospect->getContratTrav())
                array_push($docsLoc, "Contrat de travail");
            if (!$prospect->getBullSalLoc())
                array_push($docsLoc, "3 derniers bulletins de salaire");
            if (!$prospect->getAvisImpLoc())
                array_push($docsLoc, "Dernier avis d'imposition");
        }
//        GARANT
        if (!$prospect->getCniGarant())
            array_push($docsGarant, "Carte d’identité recto-verso");
        if (!$prospect->getLivret())
            array_push($docsGarant, "Livret de famille complet");
        if (!$prospect->getJustifDom())
            array_push($docsGarant, "Justificatif de domicile de moins de 3 mois");
        if (!$prospect->getBullSal())
            array_push($docsGarant, "3 derniers bulletins de salaire");
        if (!$prospect->getAvisImp())
            array_push($docsGarant, "Dernier avis d’imposition");
        $texte = "Bonjour ".$prospect->getNom()." ".$prospect->getPrenom()."<br><br>";
        $texte .= "Nous avons bien reçu votre demande de logement pour un appartement dans l’une de nos résidences et nous vous en remercions.<br><br>Aussi, après étude de votre dossier nous avons constaté qu'il manquait certains documents, à savoir :<br><br>";
        $texte .= "<b>Demandeur :</b><br><br>";
        foreach ($docsLoc as $doc) {
            $texte .= "- " . $doc . "<br>";
        }
        $texte .= "<br><b>Répondant financier (documents obligatoire) :</b><br><br>";
        foreach ($docsGarant as $doc) {
            $texte .= "- " . $doc . "<br>";
        }
        $texte .= "<br>Nous vous rappelons qu'aucune étude ne pourra être réalisée sans la totalité des pièces demandées.";
        $texte .= "<br><br>Restant à votre disposition,<br>Cordialement,<br><br><hr style='width: 150px'>";
        $texte .= "<img src='https://reserver..mgellogement.fr/assets/img/logo-ml.png' width='150'>";
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "logement@mgel.fr",
                        'Name' => "MGEL Logement"
                    ],
                    'To' => [
                        [
                            'Email' => $prospect->getEmail(),
                            'Name' => $prospect->getNom() . " " . $prospect->getPrenom()
                        ]
                    ],
                    'Subject' => "MGEL LOGEMENT - PM- Demande de Logement",
                    'HTMLPart' => $texte,
                ]
            ]
        ];
        $response = $this->mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    public function mailPassword($user, $pwd)
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "logement@mgel.fr",
                        'Name' => "MGEL Logement"
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' => $user->getNom() . ' ' . $user->getPrenom()
                        ]
                    ],
                    'Subject' => "Renouvellement dse votre mot de passe MGEL Logement",
                    'HTMLPart' => "<p style='text-align: justify'>Bonjour" . $user->getPrenom() . ",<br><br>
Suite à votre demande de renouvellement de mot passe, nous en avons générer un pour vous.
<br><br>
Pour toutes questions n’hesitez pas à utiliser notre rubrique « Aide » sur notre site ou à nous contacter directement au <a href='tel:03 83 54 32 67'>03 83 54 32 67</a></p>. <hr>
Votre mot de passe pour vous connecter à votre espace personnel est le suivant:<br><h1>" . $pwd . "</h1>"
                ]
            ]
        ];
        $response = $this->mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    public function mailRdvSignature($user)
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "logement@mgel.fr",
                        'Name' => "MGEL Logement"
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' => $user->getNom() . ' ' . $user->getPrenom()
                        ]
                    ],
                    'Subject' => "Renouvellement dse votre mot de passe MGEL Logement",
                    'HTMLPart' => "<p style='text-align: justify'>Bonjour" . $user->getPrenom() . ",<br><br>
Suite à votre demande de renouvellement de mot passe, nous en avons générer un pour vous.
<br><br>
Pour toutes questions n’hesitez pas à utiliser notre rubrique « Aide » sur notre site ou à nous contacter directement au <a href='tel:03 83 54 32 67'>03 83 54 32 67</a></p>."
                ]
            ]
        ];
        $response = $this->mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
    public function mailCourtSejour($cs)
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "contact@mgellogement.fr",
                        'Name' => "MGEL Logement"
                    ],
                    'To' => [
                        [
                            'Email' => $cs->getEmail(),
                            'Name' => $cs->getNom() . ' ' . $cs->getPrenom()
                        ]
                    ],
                    'Subject' => "Demande de court séjour",
                    'HTMLPart' => "<p style='text-align: justify'>Bonjour" . $cs->getPrenom() . ",<br><br>
Suite à votre demande de renouvellement de mot passe, nous en avons générer un pour vous.
<br><br>
Pour toutes questions n’hesitez pas à utiliser notre rubrique « Aide » sur notre site ou à nous contacter directement au <a href='tel:03 83 54 32 67'>03 83 54 32 67</a></p>."
                ]
            ]
        ];
        $response = $this->mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }
}
