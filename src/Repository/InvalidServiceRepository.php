<?php

namespace App\Repository;

use App\Entity\InvalidService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InvalidService|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvalidService|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvalidService[]    findAll()
 * @method InvalidService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvalidServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvalidService::class);
    }

    // /**
    //  * @return InvalidService[] Returns an array of InvalidService objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InvalidService
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
