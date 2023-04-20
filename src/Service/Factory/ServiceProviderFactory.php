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


    // seperate services by match type
    $exact = new ArrayCollection();
    $path = new ArrayCollection();
    $domain = new ArrayCollection();
    $wildcardDomain = new ArrayCollection();

    foreach ($registeredServices as $registeredService) {
      $matchMethod = $registeredService->getMatchMethod() ?? 'exact';
      if ($matchMethod == 'exact')
        $exact[] = $registeredService;
      else if ($matchMethod == 'path')
        $path[] = $registeredService;
      else if ($matchMethod == 'domain')
        $domain[] = $registeredService;
      else if ($matchMethod == 'wildcarddomain')
        $wildcardDomain = $registeredService;
    }

    // find matching service provider

    // exact
    foreach ($exact as $service) {
      $identifier = UtilityGenerator::cleanService($service->getIdentifier());
      
      if ($cleanedService == $identifier && $service->getEnabled())
        return $service;
    }

    // path
    foreach ($path as $service) {
      $identifier = UtilityGenerator::cleanService($service->getIdentifier());
      
      if (
        strpos($cleanedService, $identifier) !== false
        && $service->getEnabled()
      )
        return $service;
    }

    // domain
    foreach ($domain as $service) {
      $identifier = UtilityGenerator::cleanService($service->getIdentifier());

      if (
        strpos($cleanedService, strtok($identifier, '/')) === 0
        && $service->getEnabled()
      )
        return $service;
    }

    // wildcard domain
    foreach ($wildcardDomain as $service) {
      $identifier = UtilityGenerator::cleanService($service->getIdentifier());

      if (
        strpos($cleanedService, strtok($identifier, '/')) !== false
        && $service->getEnabled() 
      )
        return $service;
    }

    return null;
  }
}
