<?php

namespace App\Repository;

use App\Entity\Locataire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Orx;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Locataire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Locataire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Locataire[]    findAll()
 * @method Locataire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocataireRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Locataire::class);
    }

    // /**
    //  * @return Locataire[] Returns an array of Locataire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function getCountAll()
    {
            return $this->createQueryBuilder('l')
                ->select('COUNT(l)')
                ->getQuery()
                ->getSingleScalarResult();
    }

    public function getCountForYear($year, $residence = null)
    {
        if ($residence) {
            return $this->createQueryBuilder('l')
                ->andWhere('YEAR(l.date_record) = :year')
                ->andWhere('l.residence = :residence')
                ->setParameter('year', $year)
                ->setParameter('residence', $residence)
                ->select('COUNT(l)')
                ->getQuery()
                ->getSingleScalarResult();
        } else {
            return $this->createQueryBuilder('l')
                ->andWhere('YEAR(l.date_record) = :year')
                ->setParameter('year', $year)
                ->select('COUNT(l)')
                ->getQuery()
                ->getSingleScalarResult();
        }
    }

    public function getCountForMonth($month, $residence = null)
    {
        if ($residence) {
            return $this->createQueryBuilder('l')
                ->andWhere('MONTH(l.date_record) = :month')
                ->andWhere('l.residence = :residence')
                ->setParameter('month', $month)
                ->setParameter('residence', $residence)
                ->select('COUNT(l)')
                ->getQuery()
                ->getSingleScalarResult();
        } else {
            return $this->createQueryBuilder('l')
                ->andWhere('MONTH(l.date_record) = :month')
                ->setParameter('month', $month)
                ->select('COUNT(l)')
                ->getQuery()
                ->getSingleScalarResult();
        }
    }

    public function getCountForDay($day, $residence = null)
    {
        if ($residence) {
            return $this->createQueryBuilder('l')
                ->andWhere('DAY(l.date_record) = :day')
                ->andWhere('l.residence = :residence')
                ->setParameter('day', $day)
                ->setParameter('residence', $residence)
                ->select('COUNT(l)')
                ->getQuery()
                ->getSingleScalarResult();
        } else {
            return $this->createQueryBuilder('l')
                ->andWhere('DAY(l.date_record) = :day')
                ->setParameter('day', $day)
                ->select('COUNT(l)')
                ->getQuery()
                ->getSingleScalarResult();
        }
    }
    public function findAlls()
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.garant', 'g')
            ->leftJoin('l.logements', 'lo')
            ->leftJoin('lo.residence', 'r')
            ->leftJoin('r.ville', 'v')
            ->leftJoin('l.parking', 'p')
            ->addSelect('v')
            ->addSelect('r')
            ->addSelect('g')
            ->addSelect('p')
            ->addSelect('lo')
            ->orderBy('l.date_record','DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByResidence($residence)
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.garant', 'g')
            ->leftJoin('l.logements', 'lo')
            ->leftJoin('lo.residence', 'r')
            ->leftJoin('r.ville', 'v')
            ->leftJoin('l.parking', 'p')
            ->andWhere('r = :val')
            ->setParameter('val', $residence)
            ->addSelect('v')
            ->addSelect('r')
            ->addSelect('g')
            ->addSelect('p')
            ->addSelect('lo')
            ->orderBy('l.date_record','DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOne($val)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.id = :val')
            ->setParameter('val', $val)
            ->leftJoin('l.garant', 'g')
            ->leftJoin('l.logements', 'lo')
            ->leftJoin('lo.edlx', 'e')
            ->leftJoin('l.voeu', 'vo')
            ->leftJoin('lo.residence', 'r')
            ->leftJoin('r.ville', 'v')
            ->addSelect('v')
            ->addSelect('vo')
            ->addSelect('r')
            ->addSelect('e')
            ->addSelect('g')
            ->addSelect('lo')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllWithoutParking()
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.garant', 'g')
            ->leftJoin('l.parking', 'p')
            ->leftJoin('l.logement', 'lo')
            ->leftJoin('lo.edlx', 'e')
            ->leftJoin('l.voeu', 'vo')
            ->leftJoin('lo.residence', 'r')
            ->leftJoin('r.ville', 'v')
            ->addSelect('v')
            ->addSelect('p')
            ->addSelect('vo')
            ->addSelect('r')
            ->addSelect('e')
            ->addSelect('g')
            ->addSelect('lo')
            ->getQuery()
            ->getResult();
    }

    public function findAllWithoutEdle($residence = null)
    {
        if ($residence) {
            return $this->createQueryBuilder('l')
                ->leftJoin('l.edlx', 'edlx')
                ->leftJoin('l.garant', 'g')
                ->leftJoin('l.contrats', 'cont')
                ->leftJoin('l.sepa', 'sepa')
                ->leftJoin('l.parking', 'p')
                ->leftJoin('l.logements', 'lo')
                ->leftJoin('l.voeu', 'vo')
                ->leftJoin('lo.residence', 'r')
                ->leftJoin('r.ville', 'v')
                ->where('edlx IS NULL')
                ->orWhere('edlx IS NOT NULL AND edlx.type = \'EDLS\'')
                ->andWhere('r = :res')
                ->setParameter('res', $residence)
                ->addSelect('v')
                ->addSelect('edlx')
                ->addSelect('p')
                ->addSelect('vo')
                ->addSelect('sepa')
                ->addSelect('cont')
                ->addSelect('r')
                ->addSelect('g')
                ->addSelect('lo')
                ->getQuery()
                ->getResult();
        }
        return $this->createQueryBuilder('l')
            ->leftJoin('l.edlx', 'edlx')
            ->leftJoin('l.garant', 'g')
            ->leftJoin('l.sepa', 'sepa')
            ->leftJoin('l.parking', 'p')
            ->leftJoin('l.contrats', 'cont')
            ->leftJoin('l.logements', 'lo')
            ->leftJoin('l.voeu', 'vo')
            ->leftJoin('lo.residence', 'r')
            ->leftJoin('r.ville', 'v')
            ->andWhere('edlx IS NOT NULL AND edlx.type = \'EDLS\'')
            ->orWhere('edlx IS NULL')
            ->addSelect('v')
            ->addSelect('edlx')
            ->addSelect('p')
            ->addSelect('vo')
            ->addSelect('cont')
            ->addSelect('sepa')
            ->addSelect('r')
            ->addSelect('g')
            ->addSelect('lo')
            ->getQuery()
            ->getResult();
    }

    public function findAllWithoutEdls($residence = null)
    {
        if ($residence) {
            return $this->createQueryBuilder('l')
                ->leftJoin('l.edlx', 'edlx')
                ->leftJoin('l.garant', 'g')
                ->leftJoin('l.sepa', 'sepa')
                ->leftJoin('l.parking', 'p')
                ->leftJoin('l.contrats','cont')
                ->leftJoin('l.logements', 'lo')
                ->leftJoin('l.voeu', 'vo')
                ->leftJoin('lo.residence', 'r')
                ->leftJoin('r.ville', 'v')
                ->where('edlx IS NULL')
                ->orWhere('edlx IS NOT NULL AND edlx.type = \'EDLE\'')
                ->andWhere(new Orx([
                    "YEARWEEK(cont.fin) = YEARWEEK(NOW())"
                ]))
                ->andWhere('r = :res')
                ->setParameter('res', $residence)
                ->addSelect('v')
                ->addSelect('edlx')
                ->addSelect('p')
                ->addSelect('vo')
                ->addSelect('sepa')
                ->addSelect('cont')
                ->addSelect('r')
                ->addSelect('g')
                ->addSelect('lo')
                ->getQuery()
                ->getResult();
        }
        return $this->createQueryBuilder('l')
            ->leftJoin('l.edlx', 'edlx')
            ->leftJoin('l.garant', 'g')
            ->leftJoin('l.parking', 'p')
            ->leftJoin('l.logements', 'lo')
            ->leftJoin('l.contrats','cont')
            ->leftJoin('l.sepa', 'sepa')
            ->leftJoin('l.voeu', 'vo')
            ->leftJoin('lo.residence', 'r')
            ->leftJoin('r.ville', 'v')
            ->andWhere('edlx IS NOT NULL AND edlx.type = \'EDLE\'')
            ->orWhere('edlx IS NULL')
            ->andWhere(new Orx([
                "YEARWEEK(cont.fin) = YEARWEEK(NOW())"
            ]))
            ->addSelect('v')
            ->addSelect('edlx')
            ->addSelect('p')
            ->addSelect('vo')
            ->addSelect('cont')
            ->addSelect('r')
            ->addSelect('sepa')
            ->addSelect('g')
            ->addSelect('lo')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Locataire
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
