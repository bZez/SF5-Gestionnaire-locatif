<?php
namespace App\Service;


use App\Repository\ProspectRepository;

class ProspectCount
{
    public function __construct(ProspectRepository $prospectRepository)
    {
        $this->repository = $prospectRepository;
    }

    public function getNew()
    {
        return $this->repository->getCount();
    }

}