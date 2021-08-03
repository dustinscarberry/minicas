<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\InvalidService;

class InvalidServiceFactory
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

  // get invalid services limited to top 500
  public function getInvalidServices()
  {
    return $this->em
      ->getRepository(InvalidService::class)
      ->findBy([], ['created' => 'DESC'], 500);
  }
}
