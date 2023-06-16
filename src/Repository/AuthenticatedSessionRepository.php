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
  public function findAllNotExpired($maxReturn = 1000, bool $hideIncompleteSessions = false)
  {
    $query = $this->createQueryBuilder('a');

    $query->andWhere('a.expiration > :expiration');

    if ($hideIncompleteSessions)
      $query->andWhere('a.user is not null');

    return $query->setParameter('expiration', time())
      ->orderBy('a.expiration', 'DESC')
      ->setMaxResults($maxReturn)
      ->getQuery()
      ->getResult();
  }

  /**
    * @return AuthenticatedSession[] Returns array of AuthenticatedSession objects filtered by various criteria
  */
  public function findAllFiltered($service, $timeOffset, bool $expired, bool $hideIncompleteSessions = false)
  {
    $query = $this->createQueryBuilder('a');

    if (!$expired) {
      $query->andWhere('a.expiration > :expiration');
      $query->setParameter('expiration', time());
    }
    
    if ($hideIncompleteSessions)
      $query->andWhere('a.user is not null');

    if ($timeOffset) {
      $query->andWhere('a.created > :timeOffset');
      $query->setParameter('timeOffset', $timeOffset);
    }

    return $query->orderBy('a.expiration', 'DESC')
      ->getQuery()
      ->getResult();
  }

  /**
    * @return Integer Returns count of AuthenticatedSession objects in time period
  */
  public function countSessions($timeOffset = '1hour', bool $hideIncompleteSessions = false)
  {
    $query = $this->createQueryBuilder('a');

    $query->select('count(a.id) as count');
    
    if ($hideIncompleteSessions)
      $query->andWhere('a.user is not null');

    if ($timeOffset) {
      $query->andWhere('a.created > :timeOffset');
      $query->setParameter('timeOffset', $timeOffset);
    }

    return $query->getQuery()
      ->getSingleScalarResult();
  }

  /**
    * @return Integer Returns count of unique AuthenticatedSession users in time period
  */
  public function countUniqueUsers($timeOffset, bool $hideIncompleteSessions = false)
  {
    $query = $this->createQueryBuilder('a');

    $query->select('count(distinct a.user) as count');
    
    if ($hideIncompleteSessions)
      $query->andWhere('a.user is not null');

    if ($timeOffset) {
      $query->andWhere('a.created > :timeOffset');
      $query->setParameter('timeOffset', $timeOffset);
    }

    return $query->getQuery()
      ->getSingleScalarResult();
  }

  /**
    * @return void Delete AuthenticatedSession objects expired and older than specified time
  */
  public function deleteOldSessions($timeCutoff)
  {
    $em = $this->getEntityManager();
    $query = $em->createQuery(
      'DELETE
      FROM App\Entity\AuthenticatedSession a
      WHERE a.expiration < :expiration'
    )->setParameter('expiration', $timeCutoff);

    return $query->execute();
  }
}
