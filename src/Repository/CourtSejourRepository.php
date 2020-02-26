<?php

namespace App\Repository;

use App\Entity\CourtSejour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CourtSejour|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourtSejour|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourtSejour[]    findAll()
 * @method CourtSejour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourtSejourRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CourtSejour::class);
    }

    // /**
    //  * @return CourtSejour[] Returns an array of CourtSejour objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CourtSejour
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
