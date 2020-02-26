<?php

namespace App\Repository;

use App\Entity\Sepa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sepa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sepa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sepa[]    findAll()
 * @method Sepa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SepaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sepa::class);
    }

    // /**
    //  * @return Sepa[] Returns an array of Sepa objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sepa
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
