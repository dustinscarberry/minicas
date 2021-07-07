<?php

namespace App\Repository;

use App\Entity\CasTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CasTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method CasTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method CasTicket[]    findAll()
 * @method CasTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CasTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CasTicket::class);
    }

    /**
      * @return CasTicket Returns a CasTicket object by Ticket
    */
    public function findByValidTicket($ticket)
    {
      return $this->createQueryBuilder('c')
        ->andWhere('c.ticket = :ticket')
        ->andWhere('c.validated = :validated')
        ->setParameter('validated', false)
        ->setParameter('ticket', $ticket)
        ->getQuery()
        ->getOneOrNullResult();
    }
}
