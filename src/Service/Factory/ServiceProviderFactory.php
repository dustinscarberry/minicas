<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Service\Generator\UtilityGenerator;
use App\Entity\ServiceProvider;

class ServiceProviderFactory
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function createServiceProvider(ServiceProvider $serviceProvider)
  {
    $this->em->persist($serviceProvider);
    $this->em->flush();
  }

  public function updateServiceProvider(
    ServiceProvider $serviceProvider,
    ArrayCollection $originalAttributes
  )
  {
    // remove deleted services from database
    foreach ($originalAttributes as $attribute)
    {
      if ($serviceProvider->getAttributeMappings()->contains($attribute) === false)
        $this->em->remove($attribute);
    }

    $this->em->flush();
  }

  public function deleteServiceProvider($serviceProvider)
  {
    $serviceProvider->setDeleted(true);
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
      ->findAllNotDeleted();
  }

  public function getServiceIfRegistered(string $service)
  {
    // normalize service for lookup
    $cleanedService = UtilityGenerator::cleanService($service);

    // get all services to compare with
    $registeredServices = $this->em
      ->getRepository(ServiceProvider::class)
      ->findAll();

    // find matching service provider
    foreach ($registeredServices as $registeredService)
    {
      $identifier = UtilityGenerator::cleanService($registeredService->getIdentifier());

      if (
        $cleanedService == $identifier
          && $registeredService->getEnabled()
        || $registeredService->getDomainIdentifier()
          && strpos($cleanedService, strtok($identifier, '/')) === 0
          && $registeredService->getEnabled()
      )
        return $registeredService;
    }

    return null;
  }
}
