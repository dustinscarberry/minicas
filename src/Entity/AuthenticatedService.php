<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuthenticatedServiceRepository;
use JsonSerializable;

#[ORM\Entity(repositoryClass: AuthenticatedServiceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class AuthenticatedService implements JsonSerializable
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\ManyToOne(targetEntity: ServiceProvider::class)]
  #[ORM\JoinColumn(nullable: false)]
  private $service;

  #[ORM\ManyToOne(targetEntity: AuthenticatedSession::class, inversedBy: 'authenticatedServices', fetch: 'EAGER')]
  #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
  private $session;

  #[ORM\Column(type: 'text', nullable: true)]
  private $attributes;

  #[ORM\Column(type: 'string', length: 255)]
  private $trackingId;

  #[ORM\Column(type: 'string', length: 2048)]
  private $replyTo;

  #[ORM\OneToMany(targetEntity: CasTicket::class, mappedBy: 'service', orphanRemoval: true, cascade: ['persist'], fetch: 'EAGER')]
  private $casTickets;

  #[ORM\Column(type: 'integer')]
  private $created;

  #[ORM\Column(type: 'integer')]
  private $updated;

  public function __construct()
  {
    $this->casTickets = new ArrayCollection();
  }

  #[ORM\PrePersist]
  #[ORM\PreUpdate]
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

  public function getService(): ?ServiceProvider
  {
    return $this->service;
  }

  public function setService(?ServiceProvider $service): self
  {
    $this->service = $service;
    return $this;
  }

  public function getSession(): ?AuthenticatedSession
  {
    return $this->session;
  }

  public function setSession(?AuthenticatedSession $session): self
  {
    $this->session = $session;
    return $this;
  }

  public function getAttributes(): ?object
  {
    return json_decode($this->attributes);
  }

  public function setAttributes($attributes): self
  {
    if (is_array($attributes))
      $attributes = json_encode($attributes);

    $this->attributes = $attributes;
    return $this;
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

  public function getReplyTo(): ?string
  {
    return $this->replyTo;
  }

  public function setReplyTo(string $replyTo): self
  {
    $this->replyTo = $replyTo;
    return $this;
  }

  /**
   * @return Collection|CasTicket[]
   */
  public function getCasTickets(): Collection
  {
    return $this->casTickets;
  }

  public function addCasTicket(CasTicket $casTicket): self
  {
    if (!$this->casTickets->contains($casTicket)) {
      $this->casTickets[] = $casTicket;
      $casTicket->setService($this);
    }

    return $this;
  }

  public function removeCasTicket(CasTicket $casTicket): self
  {
    if ($this->casTickets->contains($casTicket)) {
      $this->casTickets->removeElement($casTicket);
      // set the owning side to null (unless already changed)
      if ($casTicket->getService() === $this)
        $casTicket->setService(null);
    }

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

  public function jsonSerialize()
  {
    return [
      'service' => $this->getService(),
      'attributes' => $this->attributes,
      'replyTo' => $this->replyTo,
      'created' => $this->created,
      'updated' => $this->updated
    ];
  }
}
