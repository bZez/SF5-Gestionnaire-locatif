<?php

namespace App\Repository;

use App\Entity\FaqTodo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FaqTodo|null find($id, $lockMode = null, $lockVersion = null)
 * @method FaqTodo|null findOneBy(array $criteria, array $orderBy = null)
 * @method FaqTodo[]    findAll()
 * @method FaqTodo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqTodoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FaqTodo::class);
    }

    /**
     * @return FaqTodo[] Returns an array of FaqTodo objects
     */
    public function findBetween($from,$to)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.date BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('q.date', 'ASC')
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return FaqTodo[] Returns an array of FaqTodo objects
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
    public function findOneBySomeField($value): ?FaqTodo
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
