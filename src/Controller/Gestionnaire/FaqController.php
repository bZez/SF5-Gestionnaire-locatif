<?php

namespace App\Controller\Gestionnaire;

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

/**
 * @Route("/faq",host="gest.mgellogement.fr")
 */
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
     * @Route("/ajout-categorie",name="add-category")
     */
    public function addCat(Request $request)
    {
        $category = new FaqCat();
        $form = $this->createForm(FaqCatType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->persist($category);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('aide');
        }
        return $this->render('gestionnaire/faq/creation-faq.html.twig', [
            'form' => $form->createView(),
            'newQuestionCount' =>$this->getQuestionCount(),
        ]);
    }

    /**
     * @Route("/",name="liste-faq")
     */
    public function listFaq(FaqRepository $faqRepository,FaqTodoRepository $faqTodoRepository, FaqCatRepository $faqCatRepository,Request $request)
    {
        $newQuestion = new Faq();

        $form = $this->createForm(FaqType::class,$newQuestion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oldTodo = $faqTodoRepository->find($request->get('idToRemove'));
            $this->getDoctrine()->getManager()->persist($newQuestion);
            $this->getDoctrine()->getManager()->remove($oldTodo);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('liste-faq');
        }
        $questions = array();
        $categories = $faqCatRepository->findAll();
        foreach ($categories as $category) {
            $questions[$category->getNom()] = $faqRepository->findBy(['categorie' => $category->getId()]);
        }
        return $this->render('gestionnaire/faq/index.html.twig', [
            'questions' => $questions,
            'categories' => $categories,
            'tab' => 'liste-faq',
            'newQuestionCount' =>$this->getQuestionCount(),
            'newQuestion' => $this->getQuestionCount('all'),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajout-question",name="add-question")
     */
    public function addQuestion(Request $request)
    {
        $faq = new Faq();
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($faq);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('aide');
        }
        return $this->render('gestionnaire/faq/creation-faq.html.twig', [
            'form' => $form->createView(),
            'newQuestionCount' =>$this->getQuestionCount()
        ]);
    }

    /**
     * @Route("/edit/{id}",name="edit-question")
     */
    public function aideEdit(Request $request, Faq $faq)
    {
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($faq);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('aide');
        }

        return $this->render('gestionnaire/faq/creation-faq.html.twig', [
            'question' => $faq,
            'form' => $form->createView(),
            'newQuestionCount' =>$this->getQuestionCount()

        ]);
    }

    /**
     * @Route("/del/{id}", name="delete-question", methods="GET|DELETE")
     */
    public function delete(Faq $faq)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($faq);
        $em->flush();
        return $this->redirectToRoute('liste-faq');
    }

    /**
     * @Route("/cat/del/{id}", name="delete-cat", methods="GET|DELETE")
     */
    public function deleteCat(FaqCat $cat, FaqRepository $faqRepository)
    {
        $questions = $faqRepository->findBy(['categorie' => $cat]);
        $em = $this->getDoctrine()->getManager();
        foreach ($questions as $question) {
            $em->remove($question);
        }
        $em->remove($cat);
        $em->flush();
        return $this->redirectToRoute('liste-faq');
    }

}
