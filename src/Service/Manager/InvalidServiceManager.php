<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\InvalidService;

class InvalidServiceManager
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function createInvalidService(string $service, string $remoteIp)
  {
    $invalidService = new InvalidService();
    $invalidService->setService($service);
    $invalidService->setRemoteIp($remoteIp);
    $this->em->persist($invalidService);
    $this->em->flush();
    return $invalidService;
  }

  public function deleteInvalidService($serviceProvider)
  {
    $this->em->remove($serviceProvider);
    $this->em->flush();
  }

  public function getInvalidServices()
  {
    return $this->em
      ->getRepository(InvalidService::class)
      ->findAll();
  }
}
