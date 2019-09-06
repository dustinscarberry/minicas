<?php

namespace App\Repository;

use App\Entity\ServiceProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ServiceProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceProvider[]    findAll()
 * @method ServiceProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceProviderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
      parent::__construct($registry, ServiceProvider::class);
    }

    /**
      * @return ServiceProvider Returns a ServiceProvider object by hashId
    */
    public function findByHashId($hashId)
    {
      return $this->createQueryBuilder('s')
        ->andWhere('s.hashId = :hashId')
        ->setParameter('hashId', $hashId)
        ->getQuery()
        ->getOneOrNullResult();
    }

    /**
      * @return ServiceProvider Return a ServiceProvider object by identifier
    */
    public function findByIdentifier($identifier)
    {
      return $this->createQueryBuilder('s')
        ->andWhere('s.identifier LIKE :identifier')
        ->setParameter('identifier', '%' . $identifier . '%')
        ->getQuery()
        ->getOneOrNullResult();
    }
}
