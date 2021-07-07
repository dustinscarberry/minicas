<?php

namespace App\Repository;

use App\Entity\Attribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Attribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attribute[]    findAll()
 * @method Attribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttributeRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Attribute::class);
  }

  /**
    * @return Attribute Returns an Attribute object by hashId
  */
  public function findByHashId($hashId)
  {
    return $this->createQueryBuilder('a')
      ->andWhere('a.hashId = :hashId')
      ->andWhere('a.deleted = :deleted')
      ->setParameter('hashId', $hashId)
      ->setParameter('deleted', false)
      ->getQuery()
      ->getOneOrNullResult();
  }


  /**
    * @return Attribute[] Returns array of Attribute objects not deleted
  */
  public function findAllNotDeleted()
  {
    return $this->createQueryBuilder('a')
      ->andWhere('a.deleted = :deleted')
      ->setParameter('deleted', false)
      ->getQuery()
      ->getResult();
  }
}
