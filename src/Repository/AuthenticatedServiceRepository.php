<?php

namespace App\Repository;

use App\Entity\AuthenticatedService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AuthenticatedService|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthenticatedService|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthenticatedService[]    findAll()
 * @method AuthenticatedService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthenticatedServiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AuthenticatedService::class);
    }

    /**
      * @return AuthenticatedService Returns an AuthenticatedService object by TrackingId
    */
    public function findByTrackingId($trackingId)
    {
      return $this->createQueryBuilder('a')
        ->andWhere('a.trackingId = :trackingId')
        ->setParameter('trackingId', $trackingId)
        ->getQuery()
        ->getOneOrNullResult();
    }
}
