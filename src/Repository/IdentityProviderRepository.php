<?php

namespace App\Repository;

use App\Entity\IdentityProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IdentityProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method IdentityProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method IdentityProvider[]    findAll()
 * @method IdentityProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdentityProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IdentityProvider::class);
    }

    /**
      * @return IdentityProvider Returns a IdentityProvider object by hashId
    */
    public function findByHashId($hashId)
    {
      return $this->createQueryBuilder('i')
        ->andWhere('i.hashId = :hashId')
        ->andWhere('i.deleted = :deleted')
        ->setParameter('hashId', $hashId)
        ->setParameter('deleted', false)
        ->getQuery()
        ->getOneOrNullResult();
    }

    /**
      * @return IdentityProvider[] Returns array of IdentityProvider objects not deleted
    */
    public function findAllNotDeleted()
    {
      return $this->createQueryBuilder('i')
        ->andWhere('i.deleted = :deleted')
        ->setParameter('deleted', false)
        ->getQuery()
        ->getResult();
    }
}
