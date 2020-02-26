<?php

namespace App\Repository;

use App\Entity\Residence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Residence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Residence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Residence[]    findAll()

 */
class ResidenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry,LogementRepository $logementRepository)
    {
        parent::__construct($registry, Residence::class);
    }

    // /**
    //  * @return Residence[] Returns an array of Residence objects
    //  */

    public function findByVille($ville) {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.logements','log')
            ->andWhere('r.ville != :ville')
            ->setParameter('ville', $ville)
            ->addSelect('log')
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

/*    public function findAlls() {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.logements','log')
            ->addSelect('log')
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }*/
    public function findAllExcept($residence)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id != :val')
            ->setParameter('val', $residence)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAlls()
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.logements','l')
            ->leftJoin('l.locataires','loc')
            ->addSelect('l')
            ->addSelect('l')
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOne($residence)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.logements','lo')
            ->leftJoin('r.ville','v')
            ->andWhere('r.id = :val')
            ->setParameter('val', $residence)
            ->addSelect('lo')
            ->addSelect('v')
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Residence
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
