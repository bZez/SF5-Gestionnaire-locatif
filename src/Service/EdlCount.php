<?php
namespace App\Service;


use App\Repository\EDLRepository;

class EdlCount
{
    public function __construct(EDLRepository $EDLRepository)
    {
        $this->repository = $EDLRepository;
    }

    public function getNew()
    {
        return $this->repository->getCount();
    }
}