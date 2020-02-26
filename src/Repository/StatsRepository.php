<?php

namespace App\Repository;

use App\Entity\Stats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Stats|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stats|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stats[]    findAll()
 * @method Stats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Stats::class);
    }

    // /**
    //  * @return Stats[] Returns an array of Stats objects
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
    public function getForEverytime($entity,$residence=null){
        if($residence) {
            return $this->createQueryBuilder('s')
                ->andWhere('s.entite = :entity')
                ->andWhere('s.residence = :residence')
                ->setParameter('entity',$entity)
                ->setParameter('residence',$residence)
                ->select('COUNT(s)')
                ->getQuery()
                ->getSingleScalarResult();
        } else {
            return $this->createQueryBuilder('s')
                ->andWhere('s.entite = :entity')
                ->setParameter('entity',$entity)
                ->select('COUNT(s)')
                ->getQuery()
                ->getSingleScalarResult();
        }
    }

    public function getForYear($entity,$year,$residence=null){
        if($residence) {
            return $this->createQueryBuilder('s')
                ->andWhere('s.entite = :entity')
                ->andWhere('s.annee = :year')
                ->andWhere('s.residence = :residence')
                ->setParameter('entity',$entity)
                ->setParameter('year',$year)
                ->setParameter('residence',$residence)
                ->select('COUNT(s)')
                ->getQuery()
                ->getSingleScalarResult();
        } else {
            return $this->createQueryBuilder('s')
                ->andWhere('s.entite = :entity')
                ->andWhere('s.annee = :year')
                ->setParameter('entity',$entity)
                ->setParameter('year',$year)
                ->select('COUNT(s)')
                ->getQuery()
                ->getSingleScalarResult();
        }
    }

    public function getForMonth($entity,$month,$residence=null){
        if($residence) {
            return $this->createQueryBuilder('s')
                ->andWhere('s.entite = :entity')
                ->andWhere('s.mois = :month')
                ->andWhere('s.residence = :residence')
                ->setParameter('entity',$entity)
                ->setParameter('month',$month)
                ->setParameter('residence',$residence)
                ->select('COUNT(s)')
                ->getQuery()
                ->getSingleScalarResult();
        } else {
            return $this->createQueryBuilder('s')
                ->andWhere('s.entite = :entity')
                ->andWhere('s.mois = :month')
                ->setParameter('entity',$entity)
                ->setParameter('month',$month)
                ->select('COUNT(s)')
                ->getQuery()
                ->getSingleScalarResult();
        }
    }

    public function getForDay($entity,$day,$residence=null){
        if($residence) {
            return $this->createQueryBuilder('s')
                ->andWhere('s.entite = :entity')
                ->andWhere('s.jour = :day')
                ->andWhere('s.residence = :residence')
                ->setParameter('entity',$entity)
                ->setParameter('day',$day)
                ->setParameter('residence',$residence)
                ->select('COUNT(s)')
                ->getQuery()
                ->getSingleScalarResult();
        } else {
            return $this->createQueryBuilder('s')
                ->andWhere('s.entite = :entity')
                ->andWhere('s.jour = :day')
                ->setParameter('entity',$entity)
                ->setParameter('day',$day)
                ->select('COUNT(s)')
                ->getQuery()
                ->getSingleScalarResult();
        }
    }

    /*
    public function findOneBySomeField($value): ?Stats
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
