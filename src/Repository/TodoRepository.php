<?php

namespace App\Repository;

use App\Entity\Todo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Todo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todo[]    findAll()
 * @method Todo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Todo::class);
    }

    // /**
    //  * @return Todo[] Returns an array of Todo objects
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

    public function findEnAttente($date,$residence = null)
    {
        if($residence)
        {
            return $this->createQueryBuilder('t')
                ->leftJoin('t.logement','log')
                ->leftJoin('log.residence','r')
                ->leftJoin('t.locataire','loc')
                ->andWhere('t.date_entree <= :val')
                ->andWhere('t.statut = \'A loger\'')
                ->andWhere('r = :res')
                ->setParameter('val', $date)
                ->setParameter('res', $residence)
                ->orderBy('t.id', 'ASC')
                ->addSelect('loc')
                ->addSelect('log')
                ->addSelect('r')
                ->getQuery()
                ->getResult()
                ;
        }
        return $this->createQueryBuilder('t')
            ->leftJoin('t.logement','log')
            ->leftJoin('t.locataire','loc')
            ->andWhere('t.date_entree <= :val')
            ->andWhere('t.statut = \'A loger\'')
            ->setParameter('val', $date)
            ->orderBy('t.id', 'ASC')
            ->addSelect('loc')
            ->addSelect('log')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findALiberer($date,$residence = null)
    {
        if($residence)
        {
            return $this->createQueryBuilder('t')
                ->leftJoin('t.logement','log')
                ->leftJoin('log.residence','r')
                ->leftJoin('t.locataire','loc')
                ->andWhere('t.date_sortie <= :val')
                ->andWhere('r = :res')
                ->andWhere('t.statut = \'A liberer\'')
                ->setParameter('val', $date)
                ->setParameter('res', $residence)
                ->orderBy('t.id', 'ASC')
                ->addSelect('loc')
                ->addSelect('log')
                ->addSelect('r')
                ->getQuery()
                ->getResult()
                ;
        }
        return $this->createQueryBuilder('t')
            ->leftJoin('t.logement','log')
            ->leftJoin('t.locataire','loc')
            ->andWhere('t.date_sortie <= :val')
            ->andWhere('t.statut = \'A liberer\'')
            ->setParameter('val', $date)
            ->orderBy('t.id', 'ASC')
            ->addSelect('loc')
            ->addSelect('log')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Todo
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
