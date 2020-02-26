<?php

namespace App\Controller\Gestionnaire;

use App\Entity\Photos;
use App\Entity\Residence;
use App\Entity\Ville;
use App\Form\PhotosType;
use App\Form\ResidenceType;
use App\Repository\LogementRepository;
use App\Repository\PhotosRepository;
use App\Repository\ResidenceRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/residence")
 */
class ResidenceController extends AbstractController
{
    /**
     * @Route("/", name="residence_index", methods={"GET"})
     */
    public function index(ResidenceRepository $residenceRepository,LogementRepository $logementRepository): Response
    {
        $residences = $residenceRepository->findAll();
        return $this->render('gestionnaire/residence/index.html.twig', [
            'residences' => $residences,
        ]);
    }

    /**
     * @Route("/new", name="residence_new", methods={"GET","POST"})
     */
    public function new(Request $request,FileUploader $fileUploader): Response
    {
//        TODO: AJOUTER CHECKBOX MEUBLEE OU NON
//        TODO: AJOUTER DATE DE REGLEMENT
//        TODO: AJOUTER TVA OU NON
        $residence = new Residence();
        $form = $this->createForm(ResidenceType::class, $residence);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            if($request->get('residence')['ville'] === 'nouvelle')
            {
                $ville = new Ville();
                $ville->setNom($request->get('ville')['nom']);
                $ville->setCodePostal($request->get('ville')['code_postal']);
                $entityManager->persist($ville);
                $entityManager->flush();
                $residence->setVille($ville);
            }
            $couverture = $residence->getCouverture();
            $fileUploader->setTargetDirectory($this->getParameter('photos_dir'));
            $couvertureName = $fileUploader->upload($couverture);
            $residence->setCouverture($couvertureName);
            $photos = $residence->getPhotos();
            foreach ($photos as $photo)
            {
                foreach ($photo as $picture)
                {
                    $pic = new Photos();
                    $fileName = $fileUploader->upload($picture);
                    $pic->setImage($fileName);
                    $entityManager->persist($pic);
                    $entityManager->flush();
                    $residence->addPhoto($pic);
                }
            }
            unset($residence->getPhotos()['image']);
            $entityManager->persist($residence);
            $entityManager->flush();

            return $this->redirectToRoute('residence_index');
        }

        return $this->render('gestionnaire/residence/new.html.twig', [
            'residence' => $residence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="residence_show", methods={"GET"})
     */
    public function show(Residence $residence,LogementRepository $logementRepository): Response
    {
        if(!$residence->getLogements()->isEmpty())
        {
            return $this->render('gestionnaire/residence/show.html.twig', [
                'residence' => $residence,
            ]);
        } else {
            return $this->redirectToRoute('logement_new',['residence' =>$residence->getNom()]);
        }
    }

    /**
     * @Route("/{id}/edit", name="residence_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Residence $residence,FileUploader $fileUploader): Response
    {
//TODO: AFFICHER LOYERS AVEC CHARGES ET COTIS + TVA

        $old_couv = $residence->getCouverture();
        $form = $this->createForm(ResidenceType::class, $residence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $couverture = $residence->getCouverture();
            $fileUploader->setTargetDirectory($this->getParameter('photos_dir'));
            if ($residence->getCouverture())
            {
                $couvertureName = $fileUploader->upload($couverture);
                $residence->setCouverture($couvertureName);
            } else {
                $residence->setCouverture($old_couv);
            }
            $photos = $residence->getPhotos();
            foreach ($photos as $photo)
            {
                foreach ($photo as $picture)
                {
                    $pic = new Photos();
                    $fileName = $fileUploader->upload($picture);
                    $pic->setImage($fileName);
                    $entityManager->persist($pic);
                    $residence->addPhoto($pic);
                }
            }
            unset($residence->getPhotos()['image']);
            $entityManager->persist($residence);
            $entityManager->flush();

            return $this->redirectToRoute('residence_index');
        }

        return $this->render('gestionnaire/residence/edit.html.twig', [
            'residence' => $residence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete/{photo_id}",name="residence_delete_photo")
     */
    public function removePhoto(Residence $residence,PhotosRepository $photosRepository,$photo_id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $photo = $photosRepository->find($photo_id);
        $entityManager->remove($photo);
        $residence->removePhoto($photo);
        $entityManager->flush();
        return $this->redirectToRoute('residence_show',['id'=>$residence->getId()]);
    }

    /**
     * @Route("/{id}", name="residence_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Residence $residence,LogementRepository $logementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$residence->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $logements = $logementRepository->findByResidence($residence);
            foreach ($logements as $logement)
            {
                $entityManager->remove($logement);
            }
            $entityManager->remove($residence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('residence_index');
    }
}
