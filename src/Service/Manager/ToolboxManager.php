<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AuthenticatedSession;
use App\Model\AppConfig;

class ToolboxManager
{
  private $em;
  private $appConfig;

  public function __construct(EntityManagerInterface $em, AppConfig $config)
  {
    $this->em = $em;
    $this->appConfig = $config;
  }

  public function cleanupExpiredSessions()
  {
    $currentTime = time();
    $startTime = strtotime('23:00:00');
    $endTime = $startTime + 3600;

    if ($currentTime < $startTime || $currentTime > $endTime)
      return;

    if ($this->appConfig->getAutoDeleteExpiredSessions() == 0)
      return;

    $deleteBefore = $currentTime - ($this->appConfig->getAutoDeleteExpiredSessions() * 86400);

    $sessions = $this->em
      ->getRepository(AuthenticatedSession::class)
      ->findAllOldSessions($deleteBefore);

    foreach ($sessions as $session)
      $this->em->remove($session);

    $this->em->flush();
  }
}
