<?php

namespace App\Repository;

use App\Entity\Contrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Contrat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrat[]    findAll()
 * @method Contrat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contrat::class);
    }

    // /**
    //  * @return Contrat[] Returns an array of Contrat objects
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
    }*/

    public function findAllUnSignedByResidence($residence)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.locataire','lo')
            ->leftJoin('lo.parking','p')
            ->leftJoin('c.logement','log')
            ->leftJoin('log.residence','r')
            ->andWhere('r = :val')
            ->setParameter('val', $residence)
            ->andWhere('c.signature = false')
            ->leftJoin('c.gen_by','u')
            ->addSelect('lo')
            ->addSelect('log')
            ->addSelect('u')
            ->addSelect('r')
            ->addSelect('p')
            ->orderBy('c.date_signature', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findAllUnSigned()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.locataire','lo')
            ->leftJoin('lo.sepa', 'sepa')
            ->leftJoin('lo.parking','p')
            ->leftJoin('c.logement','log')
            ->leftJoin('log.residence','r')
            ->andWhere('c.signature = false')
            ->leftJoin('c.gen_by','u')
            ->addSelect('lo')
            ->addSelect('log')
            ->addSelect('sepa')
            ->addSelect('u')
            ->addSelect('r')
            ->addSelect('p')
            ->orderBy('c.date_signature', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findAlls()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.locataire','lo')
            ->leftJoin('lo.parking','p')
            ->leftJoin('c.logement','log')
            ->leftJoin('c.gen_by','u')
            ->andWhere('c.signature = false')
            ->addSelect('lo')
            ->addSelect('log')
            ->addSelect('u')
            ->addSelect('p')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



    /*
    public function findOneBySomeField($value): ?Contrat
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
