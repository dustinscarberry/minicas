<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Service\Generator\HashIdGenerator;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthenticatedSessionRepository")
 * @ORM\Table(indexes={@ORM\Index(name="tracking_id_idx", columns={"tracking_id"})})
 * @ORM\Table(indexes={@ORM\Index(name="hash_id_idx", columns={"hash_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class AuthenticatedSession
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=25)
   */
  private $hashId;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $trackingId;

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
  private $user;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\AuthenticatedService", mappedBy="session", orphanRemoval=true, cascade={"persist"}, fetch="EAGER")
   */
  private $authenticatedServices;

  /**
   * @ORM\Column(type="integer")
   */
  private $expiration;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $remoteIp;

  public function __construct()
  {
    $this->authenticatedServices = new ArrayCollection();
  }

  /**
   * @ORM\PrePersist
   */
  public function createHashId()
  {
    $this->hashId = HashIdGenerator::generate();
  }

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

  public function getTrackingId(): ?string
  {
    return $this->trackingId;
  }

  public function setTrackingId(string $trackingId): self
  {
    $this->trackingId = $trackingId;
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

  public function getUser(): ?string
  {
    return $this->user;
  }

  public function setUser(?string $user): self
  {
    $this->user = $user;
    return $this;
  }

  /**
   * @return Collection|AuthenticatedService[]
   */
  public function getAuthenticatedServices(): Collection
  {
      return $this->authenticatedServices;
  }

  public function addAuthenticatedService(AuthenticatedService $authenticatedService): self
  {
      if (!$this->authenticatedServices->contains($authenticatedService)) {
          $this->authenticatedServices[] = $authenticatedService;
          $authenticatedService->setSession($this);
      }

      return $this;
  }

  public function removeAuthenticatedService(AuthenticatedService $authenticatedService): self
  {
      if ($this->authenticatedServices->contains($authenticatedService)) {
          $this->authenticatedServices->removeElement($authenticatedService);
          // set the owning side to null (unless already changed)
          if ($authenticatedService->getSession() === $this) {
              $authenticatedService->setSession(null);
          }
      }

      return $this;
  }

  public function getExpiration(): ?int
  {
      return $this->expiration;
  }

  public function setExpiration(int $expiration): self
  {
      $this->expiration = $expiration;

      return $this;
  }

  public function getHashId(): ?string
  {
      return $this->hashId;
  }

  public function setHashId(string $hashId): self
  {
      $this->hashId = $hashId;

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
