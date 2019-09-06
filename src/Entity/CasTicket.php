<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CasTicketRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CasTicket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ticket;

    /**
     * @ORM\Column(type="integer")
     */
    private $created;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AuthenticatedService", inversedBy="casTickets", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

    /**
     * @ORM\Column(type="integer")
     */
    private $expiration;

    /**
     * @ORM\PrePersist
     */
    public function setDefaults()
    {
      if ($this->validated == null)
        $this->validated = false;

      if ($this->created == null)
        $this->created = time();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicket(): ?string
    {
        return $this->ticket;
    }

    public function setTicket(string $ticket): self
    {
        $this->ticket = $ticket;

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

    public function getValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): self
    {
        $this->validated = $validated;

        return $this;
    }

    public function getService(): ?AuthenticatedService
    {
        return $this->service;
    }

    public function setService(?AuthenticatedService $service): self
    {
        $this->service = $service;

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
}
