<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AuthenticatedSession;
use App\Entity\AuthenticatedService;
use App\Entity\ServiceProvider;
use App\Service\Generator\SAML2Generator;
use App\Service\Generator\AuthGenerator;

/**
 * Factory class to work with Authenticated Services
 *
 * @package DAS
 * @author Dustin Scarberry <dustin@codeclouds.net>
 */
class AuthenticatedServiceFactory
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  /** Create new AuthenticatedService
   *
   * @param ServiceProvider $serviceProvider
   * @param AuthenticatedSession $authenticatedSession
   * @param string $replyTo
   * @return AuthenticatedService
   */
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

  /**
   * Get AuthenticatedService by tracking id
   *
   * @param string $trackingId
   * @return AuthenticatedService|null
   */
  public function getServiceByTrackingId($trackingId)
  {
    return $this->em
      ->getRepository(AuthenticatedService::class)
      ->findByTrackingId($trackingId);
  }

  /**
   * Update AuthenticatedService with attribute mappings from LDAP
   * @param AuthenticatedService $authenticatedService
   * @param string $username
   * @return AuthenticatedService
   */
  public function mapServiceAttributes(
    AuthenticatedService $authenticatedService,
    string $username
  )
  {
    // get service provider ref
    $serviceProvider = $authenticatedService->getService();

    // get attribute mappings of service provider
    $attributeMappings = $serviceProvider->getAttributeMappings();
    // get identity provider user filter
    $userFilterAttributeMapping = $serviceProvider->getIdentityProvider()
      ->getUserAttributeMapping()
      ->getAdAttribute();

    // get user attribute override mapping for service provider if specified
    if ($serviceProvider->getUserAttribute())
      $userAttributeMapping = $registeredService->getUserAttribute()->getAdAttribute();
    else
      $userAttributeMapping = null;

    // get mapped attributes for authenticated user
    $mappedAttributes = AuthGenerator::resolveAttributes(
      $username,
      $userFilterAttributeMapping,
      $attributeMappings,
      $userAttributeMapping
    );

    // update user session
    $authenticatedService->setAttributes($mappedAttributes);
    $this->em->flush();

    // return updated authenticated service
    return $authenticatedService;
  }

  /**
   * Return a valid AuthenticatedService from a session if found
   *
   * @param AuthenticatedSession $authenticatedSession
   * @param ServiceProvider $serviceProvider
   * @return AuthenticatedService|null
   */
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
