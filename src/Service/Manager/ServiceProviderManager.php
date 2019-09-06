<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ServiceProvider;

class ServiceProviderManager
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function createServiceProvider($serviceProvider)
  {
  }

  public function updateServiceProvider($serviceProvider)
  {
  }

  public function deleteServiceProvider($serviceProvider)
  {
    $this->em->remove($serviceProvider);
    $this->em->flush();
  }

  public function getServiceProvider($hashId)
  {
    return $this->em
      ->getRepository(ServiceProvider::class)
      ->findByHashId($hashId);
  }

  public function getServiceProviders()
  {
    return $this->em
      ->getRepository(ServiceProvider::class)
      ->findAll();
  }
}
