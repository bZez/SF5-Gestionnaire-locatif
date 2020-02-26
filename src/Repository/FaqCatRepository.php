<?php

namespace App\Repository;

use App\Entity\FaqCat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FaqCat|null find($id, $lockMode = null, $lockVersion = null)
 * @method FaqCat|null findOneBy(array $criteria, array $orderBy = null)
 * @method FaqCat[]    findAll()
 * @method FaqCat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqCatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FaqCat::class);
    }

    // /**
    //  * @return FaqCat[] Returns an array of FaqCat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FaqCat
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
