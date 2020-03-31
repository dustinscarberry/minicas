<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Setting;

class AppConfig
{
  private $locale;
  private $language;
  private $siteName;
  private $siteTimezone;
  private $sessionTimeout;
  private $casTicketTimeout;
  private $autoDeleteExpiredSessions;

  private $em;
  private $loadedSettings;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
    $this->load();
  }

  public function getLanguage(): string
  {
    return $this->language;
  }

  public function setLanguage(string $language): self
  {
    $this->language = $language;
    return $this;
  }

  public function getLocale(): string
  {
    return $this->locale;
  }

  public function setLocale(string $locale): self
  {
    $this->locale = $locale;
    return $this;
  }

  public function getSiteName(): ?string
  {
    return $this->siteName;
  }

  public function setSiteName(?string $siteName): self
  {
    $this->siteName = $siteName;
    return $this;
  }

  public function getSiteTimezone(): string
  {
    return $this->siteTimezone;
  }

  public function setSiteTimezone(string $siteTimezone): self
  {
    $this->siteTimezone = $siteTimezone;
    return $this;
  }

  public function getSessionTimeout(): int
  {
    return $this->sessionTimeout;
  }

  public function setSessionTimeout(int $sessionTimeout): self
  {
    $this->sessionTimeout = $sessionTimeout;
    return $this;
  }

  public function getCasTicketTimeout(): int
  {
    return $this->casTicketTimeout;
  }

  public function setCasTicketTimeout(int $casTicketTimeout): self
  {
    $this->casTicketTimeout = $casTicketTimeout;
    return $this;
  }

  public function getAutoDeleteExpiredSessions(): int
  {
    return $this->autoDeleteExpiredSessions;
  }

  public function setAutoDeleteExpiredSessions(int $autoDelete): self
  {
    $this->autoDeleteExpiredSessions = $autoDelete;
    return $this;
  }

  //load settings from database
  private function load(): self
  {
    $allSettings = $this->em
      ->getRepository(Setting::class)
      ->findAll();
    $this->loadedSettings = [];

    foreach ($allSettings as $setting)
      $this->loadedSettings[$setting->getName()] = $setting->getValue();

    $this->assign();

    return $this;
  }

  //save settings to database
  public function save(): self
  {
    $repository = $this->em->getRepository(Setting::class);

    foreach ($this->loadedSettings as $key => $value)
    {
      $setting = $repository->findOneByName($key);

      if ($setting)
        $setting->setValue($this->$key);
    }

    $this->em->flush();

    return $this;
  }

  private function assign()
  {
    foreach ($this->loadedSettings as $key => $value)
      $this->$key = $value;
  }
}
