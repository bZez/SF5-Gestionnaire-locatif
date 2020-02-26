<?php

namespace App\Repository;

use App\Entity\TypeLogementTarif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeLogementTarif|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeLogementTarif|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeLogementTarif[]    findAll()
 * @method TypeLogementTarif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeLogementTarifRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeLogementTarif::class);
    }

    // /**
    //  * @return TypeLogementTarif[] Returns an array of TypeLogementTarif objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findByResidence($residence)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.residence = :val')
            ->setParameter('val', $residence)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findFor($residence,$categorie)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.residence = :res')
            ->andWhere('t.categorie = :cat')
            ->setParameter('res', $residence)
            ->setParameter('cat', $categorie)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    /*
    public function findOneBySomeField($value): ?TypeLogementTarif
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
