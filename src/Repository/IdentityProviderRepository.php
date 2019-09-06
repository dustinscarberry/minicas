<?php

namespace App\Repository;

use App\Entity\IdentityProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IdentityProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method IdentityProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method IdentityProvider[]    findAll()
 * @method IdentityProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdentityProviderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
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
        ->setParameter('hashId', $hashId)
        ->getQuery()
        ->getOneOrNullResult();
    }
}
