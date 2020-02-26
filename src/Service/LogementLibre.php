<?php
namespace App\Service;


use App\Repository\LogementRepository;

class LogementLibre
{
    public function __construct(LogementRepository $logementRepository)
    {
        $this->repository = $logementRepository;
    }

    public function getFor($residence,$date)
    {
        return $this->repository->findLibreByResidence($residence,$date);
    }
}