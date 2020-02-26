<?php

namespace App\Repository;

use App\Entity\Voeu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Voeu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voeu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voeu[]    findAll()
 * @method Voeu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoeuRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Voeu::class);
    }

     /**
      * @return Voeu[] Returns an array of Voeu objects
      */

    public function findByLogement($logement)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.logement = :val')
            ->setParameter('val', $logement)
            ->join('v.logement','lo')
            ->join('lo.locataire','loc')
            ->join('lo.residence', 'r')
            ->join('r.ville','vi')
            ->addSelect('lo')
            ->addSelect('r')
            ->addSelect('vi')
            ->addSelect('loc')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Voeu
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
