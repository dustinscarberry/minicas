<?php

namespace App\Repository;

use App\Entity\ServiceCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceCategory[]    findAll()
 * @method ServiceCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceCategoryRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ServiceCategory::class);
  }

  /**
    * @return ServiceCategory Returns an ServiceCategory object by hashId
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
    * @return ServiceCategory[] Returns array of ServiceCategory objects not deleted
  */
  public function findAllNotDeleted()
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.deleted = :deleted')
      ->setParameter('deleted', false)
      ->getQuery()
      ->getResult();
  }
}
