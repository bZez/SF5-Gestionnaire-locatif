<?php

namespace App\Controller\Front;

use App\Entity\Faq;
use App\Entity\FaqCat;
use App\Entity\FaqTodo;
use App\Form\FaqTodoType;
use App\Form\FaqType;
use App\Form\FaqCatType;
use App\Repository\FaqCatRepository;
use App\Repository\FaqRepository;
use App\Repository\FaqTodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends AbstractController
{
    public function getQuestionCount($all=null)
    {
        $faqTodoRepository = $this->getDoctrine()->getRepository(FaqTodo::class);
        $questions = $faqTodoRepository->findAll();
        $questions_count = count($questions);
        if($all){
            return $questions;
        }
        return $questions_count;
    }

    /**
     * @Route("/aide",name="aide")
     */
    public function aideIndex(FaqRepository $faqRepository, FaqCatRepository $faqCatRepository)
    {
        $questions = array();
        $categories = $faqCatRepository->findAll();
        $topQuestions = $faqRepository->findTopQuestions();
        foreach ($categories as $category) {
            $questions[$category->getNom()] = $faqRepository->findBy(['categorie' => $category->getId()]);
        }
        if(isset($_GET['q'])){
            return $this->render('front/aide/search.html.twig', [
                'questions' => $questions,
                'categories' => $categories,
                'top' => $topQuestions,
                'terme' => $_GET['q']
            ]);
        }
        return $this->render('front/aide/search.html.twig', [
            'questions' => $questions,
            'categories' => $categories,
            'top' => $topQuestions,
        ]);
    }

//    AJAX REQUEST

    /**
     * @Route("/search",name="search")
     */
    public function aide(Request $request, FaqRepository $faqRepository)
    {

        if (isset($request->request)) {

            // Get data from ajax
            $search = $request->request->get('search');

            $faqs = $faqRepository->findQuestion($search);

            if ($faqs === null) {
                // Looks like something went wrong
                return new JsonResponse(array(
                    'status' => 'Error',
                    'message' => 'Error'),
                    400);
            }
            $question_array = array();
            foreach ($faqs as $faq) {
                array_push($question_array, array(
                    'id' => $faq->getId(),
                    'question' => $faq->getQuestion(),
                    'reponse' => $faq->getReponse()
                ));
            }

            // Send all this back to client
            return new JsonResponse(array(
                'status' => 'OK',
                'message' => $question_array),
                200);
        }

        // If we reach this point, it means that something went wrong
        return new JsonResponse(array(
            'status' => 'Error',
            'message' => 'Erreur'),
            400);

    }

    /**
 * @Route("/status/{id}",name="status")
 */
    public function changeStatus(Request $request, Faq $faq)
    {

        if (isset($request->request)) {

            // Get data from ajax
            $valeur = $request->request->get('utile');
            $freq = $faq->getFrequence();
            $em =  $this->getDoctrine()->getManager();
            if ($valeur === 'OUI') {
                $faq->setFrequence($freq+1);

            } else {
                $faq->setFrequence( $freq-1);

            }
            $em->persist($faq);
            $em->flush();
            // If we reach this point, it means that something went wrong
            return new JsonResponse(array(
                'status' => 'OK',
                'message' => 'Frequence ' . $valeur . ' mise à jour !'),
                200);

        }
    }

    /**
     * @Route("/ajout")
     */
    public function addUserQuestion(Request $request)
    {

        if (isset($request->request)) {

            // Get data from ajax
            $question = new FaqTodo();
            $valeur = $request->request->get('question');
            $em =  $this->getDoctrine()->getManager();
            $question->setQuestion($valeur);
            $em->persist($question);
            $em->flush();
            // If we reach this point, it means that something went wrong
            return new JsonResponse(array(
                'status' => 'OK',
                'message' => 'Question ' . $valeur . ' ajoutée !'),
                200);

        }
    }
}
