<?php

namespace App\Controller\Gestionnaire;

use App\Repository\ContratRepository;
use App\Repository\EDLRepository;
use App\Repository\FaqTodoRepository;
use App\Repository\LocataireRepository;
use App\Repository\LogementRepository;
use App\Repository\ResidenceRepository;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/",host="gest.mgellogement.fr")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard/{resid}",defaults={"resid" = null}, name="dashboard_index")
     */
    public function index($resid,ResidenceRepository $residenceRepository,ContratRepository $contratRepository,TodoRepository $todoRepository, EDLRepository $EDLRepository,LocataireRepository $locataireRepository,FaqTodoRepository $faqTodoRepository)
    {

//        TODO: REMPLACER POINT PAR VIRGULE DANS FLOATS
        $now = new \DateTime('now');
        $today = new \DateTime('now');
        $now2 = new \DateTime('now');
        $now = $now->format('Y-m-d');
        $now2 = $now2->format('Y-m-d');
        $now = strtotime(date("Y-m-d", strtotime($now)) . " -3 day");
        $now2 = strtotime(date("Y-m-d", strtotime($now2)) . " +3 day");
        $now = date('Y-m-d', $now);
        $now2 = date('Y-m-d', $now2);
        if($this->isGranted('ROLE_CL'))
        {
            $integrations = [];
            foreach ($this->getUser()->getResidences() as $residence) {
                array_push($integrations,$todoRepository->findEnAttente($now,$residence));
            }

        }elseif($this->isGranted('ROLE_ANIM') or $resid){
            $edlx = [];
            $rdvEdls = [];
            $rdvEdle = [];
            if ($resid)
            {
                $residence = $residenceRepository->findOneBy(['nom'=>$resid]);
                array_push($edlx,$EDLRepository->findAllForToday($today->format('Y-m-d'),$residence));
                array_push($rdvEdls,$locataireRepository->findAllWithoutEdls($residence));
                array_push($rdvEdle,$locataireRepository->findAllWithoutEdle($residence));
                $integrations = $todoRepository->findEnAttente($now);
                $liberations = $todoRepository->findALiberer($now2);
                return $this->render('gestionnaire/dashboard/index.html.twig', [
                    'residence' => $residence,
                    'contratsAsigner' => $contratRepository->findAllUnSignedByResidence($residence),
                    'integrations' => $integrations,
                    'liberations' => $liberations,
                    'edlx' => $edlx,
                    'rdvEdls' => $rdvEdls,
                    'rdvEdle' => $rdvEdle,
                    'newQuestions' => $faqTodoRepository->findAll()
                ]);
            } else {
                $residences = $this->getUser()->getResidences();
                foreach ($residences as $residence) {
                    array_push($edlx,$EDLRepository->findAllForToday($today->format('Y-m-d'),$residence));
                    array_push($rdvEdls,$locataireRepository->findAllWithoutEdls($residence));
                    array_push($rdvEdle,$locataireRepository->findAllWithoutEdle($residence));
                }
                return $this->render('gestionnaire/dashboard/index.html.twig', [
                    'residence'=> $residences[0],
                    'edlx' => $edlx,
                    'rdvEdls' => $rdvEdls,
                    'rdvEdle' => $rdvEdle,
                    'newQuestions' => $faqTodoRepository->findAll()
                ]);
            }
        }
        else {
            $integrations = $todoRepository->findEnAttente($now);
            $edlx = $EDLRepository->findAllForToday($today->format('Y-m-d'));
            $liberations = $todoRepository->findALiberer($now2);
        }
        return $this->render('gestionnaire/dashboard/index.html.twig', [
            'integrations' => $integrations,
            'liberations' => $liberations,
            'edlx' => $edlx,
            'rdvEdls' => $locataireRepository->findAllWithoutEdls(),
            'rdvEdle' => $locataireRepository->findAllWithoutEdle(),
            'newQuestions' => $faqTodoRepository->findAll(),
            'contratsAsigner' => $contratRepository->findAllUnSigned()
        ]);
    }
}
