<?php

namespace App\Repository;

use App\Entity\AttributeMapping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AttributeMapping|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttributeMapping|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttributeMapping[]    findAll()
 * @method AttributeMapping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttributeMappingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AttributeMapping::class);
    }

    // /**
    //  * @return AttributeMapping[] Returns an array of AttributeMapping objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AttributeMapping
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
