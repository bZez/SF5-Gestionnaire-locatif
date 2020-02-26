<?php

namespace App\Controller\Api;

use App\Repository\ResidenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api",host="")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/housing_list", name="housing_list",methods={"POST"})
     */
    public function getHousingList(Request $request,ResidenceRepository $residenceRepository)
    {
        $residences = $residenceRepository->findAll();
        $residences_array = array();
        foreach ($residences as $residence) {


                array_push($residences_array, array(
                    'id' => $residence->getId(),
                    'city' => $residence->getVille()->getNom(),
                    'rental_type' => 'Résidence étudiante',
                    'type' => $residence->getTypeMinMax(),
                    'area' => $residence->getSurfaceMinMax(),
                    'comment' => $residence->getIntro(),
                    'rent' => $residence->getLoyerMinMax(),
                    'charges' => $residence->getChargesMinMax(),
                    'image' => $request->getHost().'/_photos_/'.$residence->getCouverture(),
                ));

        }
        return new JsonResponse(array(
            'status' => 'Job Done !',
            'result' => $residences_array),
            200);
    }

    /**
     * @Route("/housing_details", name="housing_details",methods={"POST"})
     */
    public function getHousingDetail(Request $request,ResidenceRepository $residenceRepository)
    {
        $id = $request->get('id');
        $residence = $residenceRepository->findOneBy(['id'=>$id]);
        if($id = 19 || 24 || 28||  23|| 27|| 30) {
            $coloc = 'IS POSSIBLE';
        } else {
            $coloc = 'NON';
        }
        if($id = 25)
        {
            $duplex = 'OUI';
        } else {
            $duplex = 'NON';
        }
        if( $id = 24||29||18||30)
        {
            $meuble = 'OUI';
        } else {
            $meuble = 'NON';
        }
        $photos = [];
        foreach ($residence->getPhotos() as $photo)
        {
            array_push($photos,$request->getHost().'/_photos_/'.$photo);
        }
        $residence_details = array();
        array_push($residence_details, array(
            "id" => $residence->getId(),
            "city" => $residence->getVille()->getNom(),
            'rental_type' => 'Résidence étudiante',
            'type' => $residence->getTypeMinMax(),
            'area' => $residence->getSurfaceMinMax(),
            'comment' => $residence->getIntro(),
            "room_mate" => $coloc,
            "duplex" =>$duplex,
            "mezzanine" => 'N/C',
            "floor" => 'N/C',
            "address_number" =>'N/C',
            "address" => $residence->getAdresse(),
            "additional_address" => 'N/C',
            "postal_code" =>$residence->getVille()->getCodePostal(),
            "geo_area" => 'N/C',
            "diag_ges" => $residence->getEmiGaz(),
            "diag_energy" => $residence->getConsoEner(),
            "nb_room" => 'N/C',
            "furniture" => $meuble,
            "rent" => $residence->getLoyerMinMax(),
            "charges" => $residence->getChargesMinMax(),
            "heating_included" => 'OUI',
            "caution" => $residence->getGarantie(),
            "allocation" => 'APL',
            "available_date" => new \DateTime('now'),
            "parking" =>'OUI',
            "garage" => 'NON',
            "elevator" => 'OUI',
            "cellar" => 'NON',
            "bike" => 'OUI',
            "digicode" => 'OUI',
            "intercom" =>'NON',
            "caretaking" => 'OUI',
            "connection" => 'OUI',
            "other_premises" => $residence->getServices(),
            "apt_kitchenette" => 'OUI',
            "apt_equipped_kitchen" => 'OUI',
            "apt_equipped_separate" =>'NON',
            "apt_bathroom" => 'OUI',
            "apt_shower" => 'N/C',
            "apt_bath" =>'N/C',
            "apt_washbasin" => 'OUI',
            "apt_wc" => 'OUI',
            "apt_bearing" =>'NON',
            "apt_other" =>'N/C',
            'image' => $photos,
        ));
        return new JsonResponse(array(
            'status' => 'Job done !',
            'result' => $residence_details),
            200);
    }
}
