<?php

namespace App\Repository;

use App\Entity\Logement;
use App\Entity\Residence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Logement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Logement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Logement[]    findAll()
 * @method Logement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Logement::class);
    }

     /**
      * @return Logement[] Returns an array of Logement objects
      */

    public function findByResidence($residence)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.residence = :val')
            ->setParameter('val', $residence)
            ->leftJoin('l.locataires','loc')
            ->leftJoin('loc.parking','pa')
            ->leftJoin('loc.voeu','v')
            ->addSelect('loc')
            ->addSelect('v')
            ->addSelect('pa')
            ->orderBy('l.numero', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLibreByResidenceWithoutDate($residence)
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.residence','r')
            ->leftJoin('r.ville','v')
            ->andWhere('r.id = :val')
            ->andWhere('l.occupation = false')
            ->setParameter('val', $residence)
            ->addSelect('r')
            ->addSelect('v')
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findLibreByResidence($residence,$date)
{
    return $this->createQueryBuilder('l')
        ->leftJoin('l.residence','r')
        ->leftJoin('r.ville','v')
        ->andWhere('r = :val')
        ->andWhere('l.date_libre IS null OR l.date_libre <= :entree')
        ->setParameter('val', $residence)
        ->setParameter('entree', $date)
        ->addSelect('r')
        ->addSelect('v')
        ->orderBy('l.id', 'ASC')
        ->getQuery()
        ->getResult()
        ;
}

    public function findOne($id)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.id = :val')
            ->setParameter('val', $id)
            ->leftJoin('l.locataires','loc')
            ->leftJoin('loc.garant','g')
            ->leftJoin('l.residence','r')
            ->leftJoin('r.ville','vi')
            ->leftJoin('loc.voeu','v')
            ->addSelect('loc')
            ->addSelect('v')
            ->addSelect('g')
            ->addSelect('r')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Logement
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
