<?php

namespace App\Model;

class Setup
{
  private $adminUsername;
  private $adminFirstName;
  private $adminLastName;
  private $adminEmail;
  private $adminPassword;

  private $siteName = 'DAS';
  private $locale = 'en';
  private $language = 'en';
  private $siteTimezone = 'America/New_York';
  private $sessionTimeout = 60; // in minutes
  private $casTicketTimeout = 1; // in minutes
  private $autoDeleteExpiredSessions = 0; // 0 means never delete, else number of days

  public function getAdminUsername(): ?string
  {
    return $this->adminUsername;
  }

  public function setAdminUsername(string $adminUsername): self
  {
    $this->adminUsername = $adminUsername;
    return $this;
  }

  public function getAdminFirstName(): ?string
  {
    return $this->adminFirstName;
  }

  public function setAdminFirstName(string $adminFirstName): self
  {
    $this->adminFirstName = $adminFirstName;
    return $this;
  }

  public function getAdminLastName(): ?string
  {
    return $this->adminLastName;
  }

  public function setAdminLastName(string $adminLastName): self
  {
    $this->adminLastName = $adminLastName;
    return $this;
  }

  public function getAdminEmail(): ?string
  {
    return $this->adminEmail;
  }

  public function setAdminEmail(string $adminEmail): self
  {
    $this->adminEmail = $adminEmail;
    return $this;
  }

  public function getAdminPassword(): ?string
  {
    return $this->adminPassword;
  }

  public function setAdminPassword(string $adminPassword): self
  {
    $this->adminPassword = $adminPassword;
    return $this;
  }

  public function getLanguage(): ?string
  {
    return $this->language;
  }

  public function setLanguage(string $language): self
  {
    $this->language = $language;
    return $this;
  }

  public function getLocale(): ?string
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

  public function getSiteTimezone(): ?string
  {
    return $this->siteTimezone;
  }

  public function setSiteTimezone(string $siteTimezone): self
  {
    $this->siteTimezone = $siteTimezone;
    return $this;
  }

  public function getSessionTimeout(): ?int
  {
    return $this->sessionTimeout;
  }

  public function setSessionTimeout(int $sessionTimeout): self
  {
    $this->sessionTimeout = $sessionTimeout;
    return $this;
  }

  public function getCasTicketTimeout(): ?int
  {
    return $this->casTicketTimeout;
  }

  public function setCasTicketTimeout(int $casTicketTimeout): self
  {
    $this->casTicketTimeout = $casTicketTimeout;
    return $this;
  }

  public function getAutoDeleteExpiredSessions(): ?int
  {
    return $this->autoDeleteExpiredSessions;
  }

  public function setAutoDeleteExpiredSessions(int $autoDeleteExpiredSessions): self
  {
    $this->autoDeleteExpiredSessions = $autoDeleteExpiredSessions;
    return $this;
  }
}
