<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Service\Generator\HashIdGenerator;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceProviderRepository")
 * @ORM\Table(indexes={@ORM\Index(name="sp_hashid_idx", columns={"hash_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class ServiceProvider
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
  private $name;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $type;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $identifier;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\IdentityProvider", inversedBy="serviceProviders")
   * @ORM\JoinColumn(nullable=false)
   */
  private $identityProvider;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\AttributeMapping", mappedBy="service", cascade={"persist"}, orphanRemoval=true)
   */
  private $attributeMappings;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\Attribute")
   */
  private $userAttribute;

  /**
   * @ORM\Column(type="boolean")
   */
  private $enabled;

  /**
   * @ORM\Column(type="boolean")
   */
  private $deleted;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $matchMethod;

  /**
   * @ORM\ManyToOne(targetEntity=ServiceCategory::class, inversedBy="serviceProviders")
   * @ORM\JoinColumn(nullable=true)
   */
  private $category;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $contact;

  /**
   * @ORM\Column(type="text", nullable=true)
   */
  private $notes;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $environment;

  public function __construct()
  {
    $this->attributeMappings = new ArrayCollection();
    $this->enabled = true;
  }

  /**
   * @ORM\PrePersist
   */
  public function createHashId()
  {
    if (!$this->hashId)
      $this->hashId = HashIdGenerator::generate();
  }

  /**
   * @ORM\PrePersist
   */
  public function setDefaults()
  {
    if (!$this->deleted)
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

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;
    return $this;
  }

  public function getType(): ?string
  {
    return $this->type;
  }

  public function setType(string $type): self
  {
    $this->type = $type;
    return $this;
  }

  public function getIdentifier(): ?string
  {
    return $this->identifier;
  }

  public function setIdentifier(string $identifier): self
  {
    $this->identifier = $identifier;
    return $this;
  }

  public function getIdentityProvider(): ?IdentityProvider
  {
    return $this->identityProvider;
  }

  public function setIdentityProvider(?IdentityProvider $identityProvider): self
  {
    $this->identityProvider = $identityProvider;
    return $this;
  }

  /**
   * @return Collection|AttributeMapping[]
   */
  public function getAttributeMappings(): Collection
  {
      return $this->attributeMappings;
  }

  public function addAttributeMapping(AttributeMapping $attributeMapping): self
  {
    if (!$this->attributeMappings->contains($attributeMapping)) {
      $this->attributeMappings[] = $attributeMapping;
      $attributeMapping->setService($this);
    }

    return $this;
  }

  public function removeAttributeMapping(AttributeMapping $attributeMapping): self
  {
    if ($this->attributeMappings->contains($attributeMapping)) {
      $this->attributeMappings->removeElement($attributeMapping);
      // set the owning side to null (unless already changed)
      if ($attributeMapping->getService() === $this)
        $attributeMapping->setService(null);
    }

    return $this;
  }

  public function getUserAttribute(): ?Attribute
  {
    return $this->userAttribute;
  }

  public function setUserAttribute(?Attribute $userAttribute): self
  {
    $this->userAttribute = $userAttribute;
    return $this;
  }

  public function getEnabled(): ?bool
  {
    return $this->enabled;
  }

  public function setEnabled(bool $enabled): self
  {
    $this->enabled = $enabled;
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

  public function getMatchMethod(): ?string
  {
      return $this->matchMethod;
  }

  public function setMatchMethod(string $matchMethod): self
  {
      $this->matchMethod = $matchMethod;

      return $this;
  }

  public function getCategory(): ?ServiceCategory
  {
      return $this->category;
  }

  public function setCategory(?ServiceCategory $category): self
  {
      $this->category = $category;

      return $this;
  }

  public function getContact(): ?string
  {
      return $this->contact;
  }

  public function setContact(?string $contact): self
  {
      $this->contact = $contact;

      return $this;
  }

  public function getNotes(): ?string
  {
      return $this->notes;
  }

  public function setNotes(?string $notes): self
  {
      $this->notes = $notes;

      return $this;
  }

  public function getEnvironment(): ?string
  {
      return $this->environment;
  }

  public function setEnvironment(?string $environment): self
  {
      $this->environment = $environment;

      return $this;
  }
}
