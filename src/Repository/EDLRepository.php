<?php

namespace App\Repository;

use App\Entity\EDL;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EDL|null find($id, $lockMode = null, $lockVersion = null)
 * @method EDL|null findOneBy(array $criteria, array $orderBy = null)
 * @method EDL[]    findAll()
 * @method EDL[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EDLRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EDL::class);
    }

    public function getCount()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT id FROM edl WHERE edl.statut IS NULL OR edl.statut = 0';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->rowCount();
    }

    public function findByResidence($residence)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.logement', 'lo')
            ->leftJoin('lo.residence', 'r')
            ->leftJoin('lo.contrats', 'cont')
            ->andWhere('r = :val')
            ->andWhere('e.statut = false')
            ->orWhere('e.statut IS NULL')
            ->setParameter('val', $residence)
            ->leftJoin('r.ville', 'v')
            ->addSelect('lo')
            ->addSelect('cont')
            ->addSelect('r')
            ->addSelect('v')
            ->getQuery()
            ->getResult();
    }

    public function findAllForToday($date, $residence = null)
    {
        if ($residence) {
            return $this->createQueryBuilder('e')
                ->leftJoin('e.logement', 'lo')
                ->leftJoin('lo.residence', 'r')
                ->andWhere('r = :res')
                ->andWhere('DATE_FORMAT(e.date,\'%Y-%m-%d\') = :val')
                ->andWhere('e.statut = false OR e.statut IS NULL')
                ->setParameter('val', $date)
                ->setParameter('res', $residence)
                ->leftJoin('r.ville', 'v')
                ->addSelect('lo')
                ->addSelect('r')
                ->addSelect('v')
                ->getQuery()
                ->getResult();
        }
        return $this->createQueryBuilder('e')
            ->leftJoin('e.logement', 'lo')
            ->leftJoin('lo.residence', 'r')
            ->andWhere('DATE_FORMAT(e.date,\'%Y-%m-%d\') = :val')
            ->andWhere('e.statut = false OR e.statut IS NULL')
            ->setParameter('val', $date)
            ->leftJoin('r.ville', 'v')
            ->addSelect('lo')
            ->addSelect('r')
            ->addSelect('v')
            ->getQuery()
            ->getResult();
    }

    public function findAllManquant()
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.logement', 'lo')
            ->leftJoin('lo.residence', 'r')
            ->andWhere('e.doc_manquant NOT LIKE \'%[]%\'')
            ->leftJoin('r.ville', 'v')
            ->addSelect('lo')
            ->addSelect('r')
            ->addSelect('v')
            ->getQuery()
            ->getResult();
    }

    public function findManquantByResidence($residence)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.logement', 'lo')
            ->leftJoin('lo.residence', 'r')
            ->andWhere('r = :val')
            ->setParameter('val', $residence)
            ->leftJoin('r.ville', 'v')
            ->addSelect('lo')
            ->addSelect('r')
            ->addSelect('v')
            ->getQuery()
            ->getResult();
    }

    public function findAlls()
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.logement', 'lo')
            ->leftJoin('lo.contrats', 'cont')
            ->leftJoin('lo.residence', 'r')
            ->leftJoin('r.ville', 'v')
            ->andWhere('e.statut = false')
            ->orWhere('e.statut IS NULL')
            ->addSelect('lo')
            ->addSelect('r')
            ->addSelect('cont')
            ->addSelect('v')
            ->getQuery()
            ->getResult();
    }

    public function findEDLEHistory($residence = null)
    {
        if ($residence) {
            return $this->createQueryBuilder('e')
                ->leftJoin('e.logement', 'lo')
                ->leftJoin('lo.locataires', 'loc')
                ->leftJoin('lo.contrats', 'cont')
                ->leftJoin('lo.residence', 'r')
                ->leftJoin('loc.parking', 'park')
                ->leftJoin('r.ville', 'v')
                ->andWhere('r = :res')
                ->andWhere('e.statut = true')
                ->andWhere('e.type = \'EDLE\'')
                ->setParameter('res', $residence)
                ->addSelect('lo')
                ->addSelect('loc')
                ->addSelect('park')
                ->addSelect('r')
                ->addSelect('cont')
                ->addSelect('v')
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('e')
                ->leftJoin('e.logement', 'lo')
                ->leftJoin('lo.locataires', 'loc')
                ->leftJoin('lo.contrats', 'cont')
                ->leftJoin('lo.residence', 'r')
                ->leftJoin('loc.parking', 'park')
                ->leftJoin('r.ville', 'v')
                ->andWhere('e.statut = true')
                ->andWhere('e.type = \'EDLE\'')
                ->addSelect('lo')
                ->addSelect('loc')
                ->addSelect('park')
                ->addSelect('r')
                ->addSelect('cont')
                ->addSelect('v')
                ->getQuery()
                ->getResult();
        }
    }
    public function findEDLSHistory($residence = null)
    {
        if ($residence) {
            return $this->createQueryBuilder('e')
                ->leftJoin('e.logement', 'lo')
                ->leftJoin('lo.locataires', 'loc')
                ->leftJoin('lo.contrats', 'cont')
                ->leftJoin('lo.residence', 'r')
                ->leftJoin('loc.parking', 'park')
                ->leftJoin('r.ville', 'v')
                ->andWhere('r = :res')
                ->andWhere('e.statut = true')
                ->andWhere('e.type = \'EDLS\'')
                ->setParameter('res', $residence)
                ->addSelect('lo')
                ->addSelect('loc')
                ->addSelect('park')
                ->addSelect('r')
                ->addSelect('cont')
                ->addSelect('v')
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('e')
                ->leftJoin('e.logement', 'lo')
                ->leftJoin('lo.locataires', 'loc')
                ->leftJoin('lo.contrats', 'cont')
                ->leftJoin('lo.residence', 'r')
                ->leftJoin('loc.parking', 'park')
                ->leftJoin('r.ville', 'v')
                ->andWhere('e.statut = true')
                ->andWhere('e.type = \'EDLS\'')
                ->addSelect('lo')
                ->addSelect('loc')
                ->addSelect('r')
                ->addSelect('cont')
                ->addSelect('park')
                ->addSelect('v')
                ->getQuery()
                ->getResult();
        }
    }

// /**
//  * @return EDL[] Returns an array of EDL objects
//  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EDL
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
