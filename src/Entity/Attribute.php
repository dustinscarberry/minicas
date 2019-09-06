<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Service\Generator\HashIdGenerator;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttributeRepository")
 * @ORM\Table(indexes={@ORM\Index(name="attribute_hashid_idx", columns={"hash_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class Attribute
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
  private $friendlyName;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $adAttribute;

  /**
   * @ORM\Column(type="boolean")
   */
  private $deleted;

  /**
   * @ORM\PrePersist
   */
  public function createHashId()
  {
    $this->hashId = HashIdGenerator::generate();
  }

  /**
   * @ORM\PrePersist
   */
  public function setDefaults()
  {
    $this->deleted = false;
  }

  public function getId(): ?int
  {
    return $this->id;
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

  public function getAdAttribute(): ?string
  {
    return $this->adAttribute;
  }

  public function setAdAttribute(string $adAttribute): self
  {
    $this->adAttribute = $adAttribute;
    return $this;
  }

  public function getFriendlyName(): ?string
  {
    return $this->friendlyName;
  }

  public function setFriendlyName(string $friendlyName): self
  {
    $this->friendlyName = $friendlyName;
    return $this;
  }

  public function getDeleted(): ?bool
  {
      return $this->deleted;
  }

  public function setDeleted(bool $deleted): self
  {
      $this->deleted = $deleted;

      return $this;
  }
}
