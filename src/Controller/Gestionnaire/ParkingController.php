<?php

namespace App\Controller\Gestionnaire;

use App\Entity\Locataire;
use App\Entity\Parking;
use App\Form\ParkingType;
use App\Repository\LocataireRepository;
use App\Repository\ParkingRepository;
use App\Repository\ResidenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/parking")
 */
class ParkingController extends AbstractController
{
    /**
     * @Route("/{resid}",defaults={"resid"="tous"}, name="parking_index", methods={"GET"})
     */
    public function index(ParkingRepository $parkingRepository,ResidenceRepository $residenceRepository,$resid): Response
    {
        $residence = $residenceRepository->findOneBy(['nom' => $resid]);
        if ($resid === 'tous') {
            $parkings = $parkingRepository->findAlls();
        } else {
            $parkings = $parkingRepository->findByResidence($residence);
        }
        return $this->render('gestionnaire/parking/index.html.twig', [
            'parkings' => $parkings,
            'residenceChoisie' => $residence,
            'residences' => $residenceRepository->findAll()
        ]);
    }

    /**
     * @Route("/a/creation", name="parking_new", methods={"GET","POST"})
     */
    public function new(Request $request,ResidenceRepository $residenceRepository): Response
    {
        $parking = new Parking();
        $form = $this->createForm(ParkingType::class, $parking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $residence = $residenceRepository->find($request->request->get('parking')['residence']);
            if($request->request->get('parkingNbr') != null)
            {
                $nbr = $request->request->get('parkingNbr');
                for ($i=0;$i<$nbr;$i++)
                {
                    $parking = new Parking();
                    $parking->setResidence($residence);
                    $parking->setSituation($request->request->get('parking')['situation']);
                    $parking->setNumPlace($request->request->get('parking')['num_place']+$i);
                    $entityManager->persist($parking);
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('parking_index');
        }

        return $this->render('gestionnaire/parking/new.html.twig', [
            'parking' => $parking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{resid}/{id}", name="parking_show", methods={"GET"})
     */
    public function show(Parking $parking): Response
    {
        return $this->render('gestionnaire/parking/show.html.twig', [
            'parking' => $parking,
        ]);
    }

    /**
     * @Route("/{resid}/{id}/edit", name="parking_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Parking $parking): Response
    {
        $form = $this->createForm(ParkingType::class, $parking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parking_index', [
                'id' => $parking->getId(),
            ]);
        }

        return $this->render('gestionnaire/parking/edit.html.twig', [
            'parking' => $parking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="parking_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Parking $parking): Response
    {
        if ($this->isCsrfTokenValid('delete' . $parking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($parking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('parking_index');
    }

    /**
     * @Route("/{resid}/{id}/{locataire}/attibuer", name="parking_attribution")
     */
    public function attribuer($resid,Request $request, Parking $parking,Locataire $locataire): Response
    {

//        TODO: CHOIX LOC RESIDENCE OU LOCATAIRE AUTRE RESIDENCE MEME VILLE OU EXTERIEUR + FENETRE SAISIE DATE DEBUT & FIN
//        TODO: Date DEBUT  = 1er DATE DEBUT POSSIBLE (Date fin contrat ancien LOC +1 jour)
//        TODO: POSSIBILITE MODIF DATE DEBUT
//        TODO: DATE FIN = DATE FIN CONTRAT LOC
//        TODO: SI RENOUVELLEMENT CONTRAT PARKING COMPRIS
//        TODO: SI DEPART LIBERATION PARKING - VOEU
        $em = $this->getDoctrine()->getManager();
        $parking->setLocataire($locataire);
        $parking->setOccupation(1);
        $em->persist($parking);
        $em->flush();
        return $this->redirectToRoute('parking_show',['id'=>$parking->getId(),'resid'=>$parking->getResidence()->getNom()]);
    }

    /**
     * @Route("/{resid}/{id}/{locataire}/liberer", name="parking_liberation")
     */
    public function liberer($resid,Request $request, Parking $parking,Locataire $locataire): Response
    {
        $em = $this->getDoctrine()->getManager();
        $parking->setLocataire(null);
        $parking->setOccupation(0);
        $em->persist($parking);
        $em->flush();
        return $this->redirectToRoute('parking_show',['id'=>$parking->getId(),'resid'=>$parking->getResidence()->getNom()]);
    }

    /**
     * @Route("/transfert/tous")
     */
    public function test(Request $request,ResidenceRepository $residenceRepository)
    {
        $em = $this->getDoctrine()->getManager('old_gest');
        $em2 = $this->getDoctrine()->getManager();
        $req = "SELECT * FROM dbo.PKG_PLACE WHERE ID_RESIDENCE = 30";
        // $req = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'LOCATAIRE'";
        $statement = $em->getConnection()->prepare($req);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach ($results as $result) {
            $parking = new Parking();
            $num_place = $result["NUM_PLACE"];
            $id_resid = $result["ID_RESIDENCE"];
            $situation = $result["ID_PKG_SITUATION"];
            if (isset($result["DATE_BLOQUE"])) {
                $date_blocage = $result["DATE_BLOQUE"];
//                $parking->setDateBlocage($date_blocage);
            };
            if (isset($result["DATE_BLOQUE_USER"])) {
                $bloque_par = $result["DATE_BLOQUE_USER"];
//                $parking->setBloquePar($bloque_par);
            };
            $residence=$residenceRepository->find($id_resid);
            $parking->setResidence($residence);
            if ($situation == 0) {
                $parking->setSituation("Aérien");
            } else {
                $parking->setSituation("Sous-Terrain");
            }
            $parking->setNumPlace($num_place);
            /*            $email = $result['MAIL_LOCATAIRE'];
                        $id_garant = $result['ID_GARANT'];
                        $id = $result['ID_LOCATAIRE'];
                        $id_tuteur = $result['ID_PARENTS'];
                        $cnil_mgel = $result['CNIL_MGEL'];
                        $cnil_part = $result['CNIL_PARTENAIRES'];
                        $id_civ = $result['ID_CIV'];
                        $existLoc = $locataireRepository->find($id);
                        $existGar = $garantRepository->find($id_garant);
                        $existPar = $tuteurRepository->find($id_tuteur);
                        $loc = $locataireRepository->find($id);
                        if($cnil_mgel === 'O'){
                            $loc->setCnilMgel(true);
                        } else {
                            $loc->setCnilMgel(false);
                        }
                        if($cnil_part === 'O'){
                            $loc->setCnilPartenaires(true);
                        } else {
                            $loc->setCnilPartenaires(false);
                        }
                        if($id_civ == '1'){
                            $loc->setCivilite('M');
                        } else {
                            $loc->setCivilite('MME');
                        }*/
            $em2->persist($parking);
            $em2->flush();
            /* if ($existLoc) {
                 //DO NOTHING !
             } else {
                 $user = new Locataire();
                 //echo $result['COLUMN_NAME'].'<br>';

                 $id_adh = $result['ID_TYPE_ADH'];
                 $id_mrh = $result['ID_TYPE_MRH'];
                 $id_civ = $result['ID_CIV'];

                 $code_insee = $result['INSEE'];
                 $externe = $result['EXTERNE'];
                 if ($result['PASS_LOCATAIRE'] != '') {
                     $password = $encoder->encodePassword($user, $result['PASS_LOCATAIRE']);
                     $user->setPassword($password);
                 }
                 $nom = $result['NOM_LOCATAIRE'];
                 $prenom = $result['PRENOM_LOCATAIRE'];
                 $date_naissance = $result['DATE_NAISSANCE_LOCATAIRE'];
                 $ville_naissance = $result['VILLE_NAISSANCE_LOCATAIRE'];
                 $adresse = $result['NOUV_ADR_LOCATAIRE'];
                 $ville = $result['NOUV_VILLE_LOCATAIRE'];
                 $code_postal = $result['NOUV_CP_LOCATAIRE'];
                 $cpl_adresse = $result['NOUV_CPL_ADR_LOCATAIRE'];
                 $tel_fixe = $result['TEL_LOCATAIRE'];
                 $tel_mobile = $result['PORTABLE_LOCATAIRE'];
                 $commentaire = $result['COMMENTAIRE'];
                 $date_record = $result['DATE_SAISIE_LOCATAIRE'];
                 $user->setId($id);
                 $user->setIdAdh($id_adh);
                 $user->setIdMrh($id_mrh);
                 $user->setIdCiv($id_civ);
 //            CREATION PARENTS
                 $parents = new Tuteur();
                 $reqParent = "SELECT * FROM dbo.PARENTS WHERE ID_PARENTS = '$id_tuteur'";
                 $statement = $em->getConnection()->prepare($reqParent);
                 $statement->execute();
                 $resultsParent = $statement->fetchAll();

                 if (!$existPar && $resultsParent) {
                     $resultsParent = $resultsParent[0];
                     $parents->setId($resultsParent['ID_PARENTS']);
                     $parents->setNom($resultsParent['NOM_PARENTS']);
                     $parents->setPrenom($resultsParent['PRENOM_PARENTS']);
                     $parents->setAdresse($resultsParent['ADR_PARENTS']);
                     $parents->setCodePostal($resultsParent['CP_PARENTS']);
                     $parents->setCplAdresse($resultsParent['CPL_ADR_PARENTS']);
                     $parents->setVille($resultsParent['VILLE_PARENTS']);
                     $parents->setPays($resultsParent['PAYS_PARENTS']);
                     $parents->setDateNaissance($resultsParent['DATE_NAISSANCE_PARENT']);
                     $parents->setTelFixe($resultsParent['TEL_PARENTS']);
                     $parents->setTelMobile($resultsParent['PORTABLE_PARENTS']);
                     $parents->setEmail($resultsParent['MAIL_PARENTS']);
                     $em2->persist($parents);
                     $em2->flush();
                     $user->setParent($parents);
                 }
 //            FIN PARENTS


 //            CREATION GARANT
                 $reqGarant = "SELECT * FROM dbo.GARANT WHERE ID_GARANT = '$id_garant'";
                 $statement = $em->getConnection()->prepare($reqGarant);
                 $statement->execute();
                 $resultsGarant = $statement->fetchAll();
                 if (!$existGar && $resultsGarant) {
                     $garant = new Garant();

                     $resultsGarant = $resultsGarant[0];
                     $garant->setId($resultsGarant['ID_GARANT']);
                     $garant->setNom($resultsGarant['NOM_GARANT']);
                     $garant->setPrenom($resultsGarant['PRENOM_GARANT']);
                     $garant->setAdresse($resultsGarant['ADR_GARANT']);
                     $garant->setCodePostal($resultsGarant['CP_GARANT']);
                     $garant->setPays($resultsGarant['PAYS_GARANT']);
                     $garant->setCplAdresse($resultsGarant['CPL_ADR_GARANT']);
                     $garant->setVille($resultsGarant['VILLE_GARANT']);
                     $garant->setDateNaissance($resultsGarant['DATE_NAISSANCE_GARANT']);
                     $garant->setTelFixe($resultsGarant['TEL_GARANT']);
                     $garant->setTelMobile($resultsGarant['PORTABLE_GARANT']);
                     $garant->setEmail($resultsGarant['MAIL_GARANT']);
                     $em2->persist($garant);
                     $em2->flush();
                     $user->setGarant($garant);
 //            FIN GARANT
                 }

                 $user->setCodeInsee($code_insee);
                 $user->setExterne($externe);
                 if ($result['MAIL_LOCATAIRE']) {
                     $user->setEmail($email);
                 }

                 $user->setNom($nom);
                 $user->setPrenom($prenom);
                 $user->setDateNaissance($date_naissance);
                 $user->setVilleNaissance($ville_naissance);
                 $user->setVille($ville);
                 $user->setAdresse($adresse);
                 $user->setCplAdresse($cpl_adresse);
                 $user->setCodePostal($code_postal);
                 $user->setTelFixe($tel_fixe);
                 $user->setTelMobile($tel_mobile);
                 $user->setCommentaire($commentaire);
                 $user->setDateRecord($date_record);


                 $em2->persist($user);
                 $em2->flush();

             }*/

        }
        die;
    }
}
