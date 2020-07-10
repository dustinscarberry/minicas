<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Setting;

/**
 * Application settings model
 *
 * @package DAS
 * @author Dustin Scarberry <dustin@codeclouds.net>
 */
class AppConfig
{
  private $em;

  private $settingList = [
    'locale' => 'en',
    'language' => 'en',
    'siteName' => 'DAS',
    'siteTimezone' => 'America/New_York',
    'sessionTimeout' => 60,
    'casTicketTimeout' => 1,
    'autoDeleteExpiredSessions' => 0,
    'isProvisioned' => false
  ];

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
    $this->load();
  }

  public function getLanguage(): string
  {
    return $this->settingList['language'];
  }

  public function setLanguage(string $language): self
  {
    $this->settingList['language'] = $language;
    return $this;
  }

  public function getLocale(): string
  {
    return $this->settingList['locale'];
  }

  public function setLocale(string $locale): self
  {
    $this->settingList['locale'] = $locale;
    return $this;
  }

  public function getSiteName(): ?string
  {
    return $this->settingList['siteName'];
  }

  public function setSiteName(?string $siteName): self
  {
    $this->settingList['siteName'] = $siteName;
    return $this;
  }

  public function getSiteTimezone(): string
  {
    return $this->settingList['siteTimezone'];
  }

  public function setSiteTimezone(string $siteTimezone): self
  {
    $this->settingList['siteTimezone'] = $siteTimezone;
    return $this;
  }

  public function getSessionTimeout(): int
  {
    return $this->settingList['sessionTimeout'];
  }

  public function setSessionTimeout(int $sessionTimeout): self
  {
    $this->settingList['sessionTimeout'] = $sessionTimeout;
    return $this;
  }

  public function getCasTicketTimeout(): int
  {
    return $this->settingList['casTicketTimeout'];
  }

  public function setCasTicketTimeout(int $casTicketTimeout): self
  {
    $this->settingList['casTicketTimeout'] = $casTicketTimeout;
    return $this;
  }

  public function getAutoDeleteExpiredSessions(): int
  {
    return $this->settingList['autoDeleteExpiredSessions'];
  }

  public function setAutoDeleteExpiredSessions(int $autoDelete): self
  {
    $this->settingList['autoDeleteExpiredSessions'] = $autoDelete;
    return $this;
  }

  public function getIsProvisioned(): bool
  {
    return $this->settingList['isProvisioned'];
  }

  public function setIsProvisioned(bool $isProvisioned): self
  {
    $this->settingList['isProvisioned'] = $isProvisioned;
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
      $this->settingList[$settingName] = $setting->getValue();
    }

    return $this;
  }

  // save settings to database
  public function save(): self
  {
    $repository = $this->em->getRepository(Setting::class);

    foreach ($this->settingList as $key => $value) {
      $setting = $repository->findOneByName($key);

      if (!$setting) {
        $setting = new Setting();
        $setting->setName($key);
        $this->em->persist($setting);
      }

      $setting->setValue($value);
    }

    $this->em->flush();
    return $this;
  }
}
