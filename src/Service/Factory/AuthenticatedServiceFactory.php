<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AuthenticatedSession;
use App\Entity\AuthenticatedService;
use App\Entity\ServiceProvider;
use App\Service\Generator\SAML2Generator;
use App\Service\Generator\AuthGenerator;

class AuthenticatedServiceFactory
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  //create new authenticated service
  public function createService(
    ServiceProvider $serviceProvider,
    AuthenticatedSession $authenticatedSession,
    ?string $replyTo = null
  )
  {
    $service = new AuthenticatedService();
    $service->setService($serviceProvider);
    $service->setTrackingId(SAML2Generator::generateID());
    $service->setSession($authenticatedSession);

    if ($replyTo)
      $service->setReplyTo($replyTo);

    $this->em->persist($service);
    $this->em->flush();

    return $service;
  }

  //get authenticated service by tracking id
  public function getServiceByTrackingId($trackingId)
  {
    return $this->em
      ->getRepository(AuthenticatedService::class)
      ->findByTrackingId($trackingId);
  }

  //update authenticated service with attribute mappings for username
  public function mapServiceAttributes(
    AuthenticatedService $authenticatedService,
    string $username
  )
  {
    //get service provider ref
    $serviceProvider = $authenticatedService->getService();

    //get attribute mappings of service provider
    $attributeMappings = $serviceProvider->getAttributeMappings();
    //get identity provider user filter
    $userFilterAttributeMapping = $serviceProvider->getIdentityProvider()
      ->getUserAttributeMapping()
      ->getAdAttribute();

    //get user attribute override mapping for service provider if specified
    if ($serviceProvider->getUserAttribute())
      $userAttributeMapping = $registeredService->getUserAttribute()->getAdAttribute();
    else
      $userAttributeMapping = null;

    //get mapped attributes for authenticated user
    $mappedAttributes = AuthGenerator::resolveAttributes(
      $username,
      $userFilterAttributeMapping,
      $attributeMappings,
      $userAttributeMapping
    );

    //update user session
    $authenticatedService->setAttributes($mappedAttributes);
    $this->em->flush();

    //return updated authenticated service
    return $authenticatedService;
  }


  //returns a valid authenticated service from a session if found or null if not
  public function getSessionService(
    AuthenticatedSession $authenticatedSession,
    ServiceProvider $serviceProvider
  )
  {
    foreach ($authenticatedSession->getAuthenticatedServices() as $authService)
    {
      if ($authService->getService() == $serviceProvider)
        return $authService;
    }

    return null;
  }
}
