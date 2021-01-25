<?php

namespace App\Entity;

use App\Repository\ServiceCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Service\Generator\HashIdGenerator;

/**
 * @ORM\Entity(repositoryClass=ServiceCategoryRepository::class)
 * @ORM\Table(indexes={@ORM\Index(name="servicecategory_hashid_idx", columns={"hash_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class ServiceCategory
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
  private $title;

  /**
   * @ORM\Column(type="integer")
   */
  private $created;

  /**
   * @ORM\Column(type="integer")
   */
  private $updated;

  /**
   * @ORM\OneToMany(targetEntity=ServiceProvider::class, mappedBy="category")
   */
  private $serviceProviders;

  /**
   * @ORM\Column(type="boolean")
   */
  private $deleted;

  public function __construct()
  {
    $this->serviceProviders = new ArrayCollection();
  }

  /**
   * @ORM\PrePersist
   */
  public function setDefaults()
  {
    if (!$this->hashId)
      $this->hashId = HashIdGenerator::generate();

    $this->deleted = false;
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

  public function getHashId(): ?string
  {
    return $this->hashId;
  }

  public function setHashId(string $hashId): self
  {
    $this->hashId = $hashId;
    return $this;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(string $title): self
  {
    $this->title = $title;
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

  /**
   * @return Collection|ServiceProvider[]
   */
  public function getServiceProviders(): Collection
  {
    return $this->serviceProviders;
  }

  public function addServiceProvider(ServiceProvider $serviceProvider): self
  {
    if (!$this->serviceProviders->contains($serviceProvider)) {
      $this->serviceProviders[] = $serviceProvider;
      $serviceProvider->setCategory($this);
    }

    return $this;
  }

  public function removeServiceProvider(ServiceProvider $serviceProvider): self
  {
    if ($this->serviceProviders->contains($serviceProvider)) {
      $this->serviceProviders->removeElement($serviceProvider);
      // set the owning side to null (unless already changed)
      if ($serviceProvider->getCategory() === $this) {
          $serviceProvider->setCategory(null);
      }
    }

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
