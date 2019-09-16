<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AuthenticatedSession;
use App\Service\Generator\SAML2Generator;
use App\Model\AppConfig;

class AuthenticatedSessionManager
{
  private $em;
  private $appConfig;

  public function __construct(EntityManagerInterface $em, AppConfig $appConfig)
  {
    $this->em = $em;
    $this->appConfig = $appConfig;
  }

  public function createSession()
  {
    $session = new AuthenticatedSession();
    $session->setTrackingId(SAML2Generator::generateID());
    $sessionTimeout = (time() + ($this->appConfig->getSessionTimeout() * 60));
    $session->setExpiration($sessionTimeout);

    $this->em->persist($session);
    $this->em->flush();

    return $session;
  }

  public function updateSessionUsername(
    AuthenticatedSession $authenticatedSession,
    string $username
  )
  {
    $authenticatedSession->setUser($username);
    $this->em->flush();

    return $authenticatedSession;
  }

  public function deleteSession($authenticatedSession)
  {
    $this->em->remove($authenticatedSession);
    $this->em->flush();
  }

  //returns a valid authenticated session if found or null if not
  public function getSession(string $hashId)
  {
    return $this->em
      ->getRepository(AuthenticatedSession::class)
      ->findByHashId($hashId);
  }

  //returns a valid authenticated session if found or null if not
  public function getSessionNotExpired(?string $cookie)
  {
    return $this->em
      ->getRepository(AuthenticatedSession::class)
      ->findByValidTrackingId($cookie);
  }

  //get sessions that are still valid or null
  public function getSessionsNotExpired()
  {
    return $this->em
      ->getRepository(AuthenticatedSession::class)
      ->findAllNotExpired();
  }
}
