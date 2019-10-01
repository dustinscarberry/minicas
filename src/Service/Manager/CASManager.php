<?php

namespace App\Service\Manager;

use App\Service\Generator\CASGenerator;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ServiceProvider;
use App\Entity\CasTicket;
use App\Entity\AuthenticatedService;
use App\Model\AppConfig;
use App\Exception\InvalidTicketException;

class CASManager
{
  private $em;
  private $appConfig;

  public function __construct(EntityManagerInterface $em, AppConfig $appConfig)
  {
    $this->em = $em;
    $this->appConfig = $appConfig;
  }

  public function getServiceIfRegistered(string $service)
  {
    //normalize service for lookup
    $cleanedService = CASGenerator::cleanService($service);

    //get all services to compare with
    $registeredServices = $this->em
      ->getRepository(ServiceProvider::class)
      ->findAll();

    //find matching service provider
    foreach ($registeredServices as $registeredService)
    {
      $identifier = CASGenerator::cleanService($registeredService);

      if ($cleanedService == $identifier && $registeredService->getEnabled())
        return $service;
    }

    return null;
  }

  public function createTicket(AuthenticatedService $authenticatedService)
  {
    $ticket = new CasTicket();
    $ticket->setTicket(CASGenerator::generateTicket());
    $ticketTimeout = (time() + ($this->appConfig->getCasTicketTimeout() * 60));
    $ticket->setExpiration($ticketTimeout);
    $ticket->setService($authenticatedService);

    $this->em->persist($ticket);
    $this->em->flush();

    return $ticket;
  }

  public function validateTicket(string $ticket = '', string $service)
  {
    //lookup cas ticket to validate session
    $validCasTicket = $this->em
      ->getRepository(CasTicket::class)
      ->findByValidTicket($ticket);

    //check if valid session found
    if (!$validCasTicket)
      throw new InvalidTicketException('Ticket ' . $ticket . ' not recognized');

    //set ticket to validated regardless of what happens next
    $validCasTicket->setValidated(true);
    $this->em->flush();

    //check if ticket is still valid
    if (($validCasTicket->getCreated() + ($this->appConfig->getCasTicketTimeout() * 60)) < time())
      throw new InvalidTicketException('Ticket ' . $ticket . ' no longer valid');

    //check if session matches service passed
    if ($validCasTicket->getService()->getReplyTo() != $service)
      throw new InvalidServiceException('CAS ticket not valid for specified service');

    return $validCasTicket;
  }
}
