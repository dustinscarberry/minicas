<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AuthenticatedSession;
use App\Entity\AuthenticatedService;
use App\Entity\ServiceProvider;
use App\Service\Generator\SAML2Generator;
use App\Model\AppConfig;

class AuthenticatedServiceManager
{
  private $em;
  private $appConfig;

  public function __construct(EntityManagerInterface $em, AppConfig $appConfig)
  {
    $this->em = $em;
    $this->appConfig = $appConfig;
  }

  public function createService(
    ServiceProvider $registeredService,
    AuthenticatedSession $session,
    ?string $replyTo = null
  )
  {
    $service = new AuthenticatedService();
    $service->setService($registeredService);
    $service->setTrackingId(SAML2Generator::generateID());
    $service->setSession($session);

    if ($replyTo)
      $service->setReplyTo($replyTo);

    $this->em->persist($service);
    $this->em->flush();

    return $service;
  }

  //returns a valid authenticated service from a session if found or null if not
  public function getSessionService(
    AuthenticatedSession $session,
    ServiceProvider $service
  )
  {
    foreach ($session->getAuthenticatedServices() as $authService)
    {
      if ($authService->getService() == $service)
        return $authService;
    }

    return null;
  }
}
