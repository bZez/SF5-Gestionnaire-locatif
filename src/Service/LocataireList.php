<?php
namespace App\Service;


use App\Repository\LocataireRepository;

class LocataireList
{
    public function __construct(LocataireRepository $locataireRepository)
    {
        $this->repository = $locataireRepository;
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }
}