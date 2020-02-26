<?php

namespace App\Service;

use App\Repository\LogementRepository;
use App\Repository\ResidenceRepository;
use App\Repository\VilleRepository;

class ResidenceGetter
{
    public function __construct(ResidenceRepository $residenceRepository,LogementRepository $logementRepository,VilleRepository $villeRepository)
    {
        $this->repository = $residenceRepository;
        $this->logementRepository = $logementRepository;
        $this->villeRepository = $villeRepository;
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }

    public function getVille()
    {
        return $this->villeRepository->findAll();
    }

    public function getLogement($id)
    {
        return $this->logementRepository->find($id);
    }

}