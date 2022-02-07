<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AuthenticatedSession;
use App\Service\Generator\SAML2Generator;
use App\Model\AppConfig;

class AuthenticatedSessionFactory
{
  private $em;
  private $appConfig;

  public function __construct(EntityManagerInterface $em, AppConfig $appConfig)
  {
    $this->em = $em;
    $this->appConfig = $appConfig;
  }

  public function createSession($remoteIp)
  {
    $session = new AuthenticatedSession();
    $session->setTrackingId(SAML2Generator::generateID());
    $sessionTimeout = (time() + ($this->appConfig->getSessionTimeout() * 60));
    $session->setExpiration($sessionTimeout);
    $session->setRemoteIp($remoteIp);

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

  public function cleanupExpiredSessions()
  {
    $currentTime = time();

    // get start time relative to timezone settings (11pm)
    $currentDate = new \DateTime('23:00:00', new \DateTimeZone($this->appConfig->getSiteTimezone()));
    $startTime = $currentDate->format('U');

    $endTime = $startTime + 3600;

    if ($currentTime < $startTime || $currentTime > $endTime)
      return;

    if ($this->appConfig->getAutoDeleteExpiredSessions() == 0)
      return;

    $deleteBefore = $currentTime - ($this->appConfig->getAutoDeleteExpiredSessions() * 86400);

    $sessionDeletion = $this->em
      ->getRepository(AuthenticatedSession::class)
      ->deleteOldSessions($deleteBefore);
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
  public function getSessionsNotExpired($hideIncompleteSessions = false)
  {
    return $this->em
      ->getRepository(AuthenticatedSession::class)
      ->findAllNotExpired(500, $hideIncompleteSessions);
  }
}
