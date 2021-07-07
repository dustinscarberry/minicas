<?php

namespace App\Repository;

use App\Entity\AuthenticatedSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AuthenticatedSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthenticatedSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthenticatedSession[]    findAll()
 * @method AuthenticatedSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthenticatedSessionRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, AuthenticatedSession::class);
  }

  /**
    * @return AuthenticatedSession Returns an AuthenticatedSession object by hashId
  */
  public function findByHashId($hashId)
  {
    return $this->createQueryBuilder('a')
      ->andWhere('a.hashId = :hashId')
      ->setParameter('hashId', $hashId)
      ->getQuery()
      ->getOneOrNullResult();
  }

  /**
    * @return AuthenticatedSession Returns an AuthenticatedSession object by TrackingId that is not expired
  */
  public function findByValidTrackingId($trackingId)
  {
    return $this->createQueryBuilder('a')
      ->andWhere('a.trackingId = :trackingId')
      ->andWhere('a.expiration > :expiration')
      ->setParameter('trackingId', $trackingId)
      ->setParameter('expiration', time())
      ->getQuery()
      ->getOneOrNullResult();
  }

  /**
    * @return AuthenticatedSession[] Returns array of AuthenticatedSession objects not expired
  */
  public function findAllNotExpired()
  {
    return $this->createQueryBuilder('a')
      ->andWhere('a.expiration > :expiration')
      ->setParameter('expiration', time())
      ->getQuery()
      ->getResult();
  }

  /**
    * @return AuthenticatedSession[] Returns array of AuthenticatedSession objects expired and older than specified time
  */
  public function findAllOldSessions($timeCutoff)
  {
    return $this->createQueryBuilder('a')
      ->andWhere('a.expiration < :expiration')
      ->setParameter('expiration', $timeCutoff)
      ->getQuery()
      ->getResult();
  }
}
