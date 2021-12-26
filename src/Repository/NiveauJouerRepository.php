<?php

namespace App\Repository;

use App\Entity\NiveauJouer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NiveauJouer|null find($id, $lockMode = null, $lockVersion = null)
 * @method NiveauJouer|null findOneBy(array $criteria, array $orderBy = null)
 * @method NiveauJouer[]    findAll()
 * @method NiveauJouer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NiveauJouerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NiveauJouer::class);
    }

    // /**
    //  * @return NiveauJouer[] Returns an array of NiveauJouer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NiveauJouer
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
