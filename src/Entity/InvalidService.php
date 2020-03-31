<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvalidServiceRepository")
 * @ORM\HasLifecycleCallbacks
 */
class InvalidService
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $service;

  /**
   * @ORM\Column(type="integer")
   */
  private $created;

  /**
   * @ORM\Column(type="integer")
   */
  private $updated;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $remoteIp;

  /**
   * @ORM\PrePersist
   * @ORM\PreUpdate
   */
  public function updateTimestamps()
  {
    $currentTime = time();
    $this->setUpdated($currentTime);

    if ($this->getCreated() == null)
      $this->setCreated($currentTime);
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getService(): ?string
  {
    return $this->service;
  }

  public function setService(string $service): self
  {
    $this->service = $service;
    return $this;
  }

  public function getCreated(): ?int
  {
    return $this->created;
  }

  public function setCreated(int $created): self
  {
    $this->created = $created;
    return $this;
  }

  public function getUpdated(): ?int
  {
      return $this->updated;
  }

  public function setUpdated(int $updated): self
  {
    $this->updated = $updated;
    return $this;
  }

  public function getRemoteIp(): ?string
  {
    return $this->remoteIp;
  }

  public function setRemoteIp(?string $remoteIp): self
  {
    $this->remoteIp = $remoteIp;
    return $this;
  }
}
