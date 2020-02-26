<?php

namespace App\Repository;

use App\Entity\Prospect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Prospect|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prospect|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prospect[]    findAll()
 * @method Prospect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProspectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Prospect::class);
    }

    public function getCount()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT id FROM prospect';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->rowCount();
    }

    public function getCountForYear(string $year)
    {

        return $this->createQueryBuilder('p')
            ->andWhere('YEAR(p.date_demande) = :annee')
            ->setParameter('annee',$year)
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

    }

    public function getCountForMonth(string $month)
    {

        return $this->createQueryBuilder('p')
            ->andWhere('MONTH(p.date_demande) = :mois')
            ->setParameter('mois',$month)
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

    }

    public function getCountForDay(string $day)
    {

        return $this->createQueryBuilder('p')
            ->andWhere('DAY(p.date_demande) = :jour')
            ->setParameter('jour',$day)
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

    }

    public function findAlls()
    {
        return $this->createQueryBuilder('p')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOne($val)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $val)
            ->leftJoin('p.residences', 'r')
            ->leftJoin('r.logements', 'lo')
            ->leftJoin('r.ville', 'v')
            ->addSelect('r')
            ->addSelect('v')
            ->addSelect('lo')
            ->getQuery()
            ->getOneOrNullResult();
    }
    /*
    public function findOneBySomeField($value): ?Prospect
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
