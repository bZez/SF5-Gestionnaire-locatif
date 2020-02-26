<?php

namespace App\Controller\Gestionnaire;

use App\Entity\Photos;
use App\Form\PhotosType;
use App\Repository\PhotosRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/photos")
 */
class PhotosController extends AbstractController
{
    /**
     * @Route("/", name="photos_index", methods={"GET"})
     */
    public function index(Request $request,PhotosRepository $photosRepository,FileUploader $fileUploader): Response
    {
        $photo = new Photos();
        $form = $this->createForm(PhotosType::class, $photo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $photo->getImage();
            $fileUploader->setTargetDirectory($this->getParameter('photos_dir'));
            $fileName = $fileUploader->upload($file);
            $photo->setImage($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photo);
            $entityManager->flush();

            return $this->redirectToRoute('photos_index');
        }
        return $this->render('gestionnaire/photos/index.html.twig', [
            'photos' => $photosRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="photos_new", methods={"GET","POST"})
     */
    public function new(Request $request,FileUploader $fileUploader): Response
    {
        $photo = new Photos();
        $form = $this->createForm(PhotosType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $files = $photo->getImage();
            $fileUploader->setTargetDirectory($this->getParameter('photos_dir'));
           foreach ($files as $file){
               $fileName = $fileUploader->upload($file);
               $photo->setImage($fileName);
               $entityManager->persist($photo);
           }

            $entityManager->flush();

            return $this->redirectToRoute('photos_index');
        }

        return $this->render('gestionnaire/photos/new.html.twig', [
            'photo' => $photo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="photos_show", methods={"GET"})
     */
    public function show(Photos $photo): Response
    {
        return $this->render('gestionnaire/photos/show.html.twig', [
            'photo' => $photo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="photos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Photos $photo): Response
    {
        $form = $this->createForm(PhotosType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('photos_index', [
                'id' => $photo->getId(),
            ]);
        }

        return $this->render('gestionnaire/photos/edit.html.twig', [
            'photo' => $photo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="photos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Photos $photo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($photo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('photos_index');
    }
}
