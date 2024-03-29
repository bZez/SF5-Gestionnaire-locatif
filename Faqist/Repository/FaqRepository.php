<?php

namespace App\Repository;

use App\Entity\Faq;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Faq|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faq|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faq[]    findAll()
 * @method Faq[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Faq::class);
    }

     /**
      * @return Faq[] Returns an array of Faq objects
      */
    public function findQuestion($value)
    {
        if($value !== null) {
            return $this->createQueryBuilder('f')
                ->where('f.question LIKE :val')
                ->orWhere('f.reponse LIKE :val')
                ->setParameter('val', '%'.$value.'%')
                ->orderBy('f.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
                ;
        }
    }

    /**
     * @return Faq[] Returns an array of Faq objects
     */
    public function findTopQuestions()
    {
        return $this->createQueryBuilder('f')
                ->where('f.frequence >= 0')
                ->orderBy('f.frequence', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
                ;
    }


    /*
    public function findOneBySomeField($value): ?Faq
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
