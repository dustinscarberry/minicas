<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Setting;

class AppConfig
{
  private $locale = 'en';
  private $language = 'en';
  private $siteName = 'DAS';
  private $siteTimezone = 'America/New_York';
  private $sessionTimeout = 60;
  private $casTicketTimeout = 1;
  private $autoDeleteExpiredSessions = 0;
  private $isProvisioned = false;

  private $em;

  // settings to monitor, all setting keys must be present
  // in array to load and save correctly
  private $settingList = [
    'locale',
    'language',
    'siteName',
    'siteTimezone',
    'sessionTimeout',
    'casTicketTimeout',
    'autoDeleteExpiredSessions',
    'isProvisioned'
  ];

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

  public function getIsProvisioned(): bool
  {
    return $this->isProvisioned;
  }

  public function setIsProvisioned(bool $isProvisioned): self
  {
    $this->isProvisioned = $isProvisioned;
    return $this;
  }

  // load settings from database
  private function load(): self
  {
    $allSettings = $this->em
      ->getRepository(Setting::class)
      ->findAll();

    foreach ($allSettings as $setting) {
      $settingName = $setting->getName();
      $this->$settingName = $setting->getValue();
    }

    return $this;
  }

  // save settings to database
  public function save(): self
  {
    $repository = $this->em->getRepository(Setting::class);

    foreach ($this->settingList as $key) {
      $setting = $repository->findOneByName($key);

      if (!$setting) {
        $setting = new Setting();
        $setting->setName($key);
        $this->em->persist($setting);
      }

      $setting->setValue($this->$key);
    }

    $this->em->flush();
    return $this;
  }
}
