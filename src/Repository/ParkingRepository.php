<?php

namespace App\Repository;

use App\Entity\Parking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Parking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parking[]    findAll()
 * @method Parking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParkingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Parking::class);
    }

    // /**
    //  * @return Parking[] Returns an array of Parking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findAlls()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->leftJoin('p.residence','r')
            ->leftJoin('r.ville','v')
            ->addSelect('r')
            ->addSelect('v')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByResidence($residence)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.residence = :val')
            ->setParameter('val', $residence)
            ->orderBy('p.id', 'ASC')
            ->leftJoin('p.residence','r')
            ->leftJoin('r.ville','v')
            ->addSelect('r')
            ->addSelect('v')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Parking
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
