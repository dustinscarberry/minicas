<?php

namespace App\Repository;

use App\Entity\ServiceProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceProvider[]    findAll()
 * @method ServiceProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
        ->andWhere('s.deleted = :deleted')
        ->setParameter('hashId', $hashId)
        ->setParameter('deleted', false)
        ->getQuery()
        ->getOneOrNullResult();
    }

    /**
      * @return ServiceProvider[] Returns array of ServiceProvider objects not deleted
    */
    public function findAllNotDeleted()
    {
      return $this->createQueryBuilder('s')
        ->andWhere('s.deleted = :deleted')
        ->setParameter('deleted', false)
        ->getQuery()
        ->getResult();
    }

    /**
      * @return ServiceProvider Return a ServiceProvider object by identifier
    */
    public function findByIdentifier($identifier)
    {
      return $this->createQueryBuilder('s')
        ->andWhere('s.identifier LIKE :identifier')
        ->andWhere('s.deleted = :deleted')
        ->setParameter('identifier', '%' . $identifier . '%')
        ->setParameter('deleted', false)
        ->getQuery()
        ->getOneOrNullResult();
    }
}
