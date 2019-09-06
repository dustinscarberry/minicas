<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Service\Generator\HashIdGenerator;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IdentityProviderRepository")
 * @ORM\Table(indexes={@ORM\Index(name="idp_hashid_idx", columns={"hash_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class IdentityProvider
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
    private $loginURL;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identifier;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $certificate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ServiceProvider", mappedBy="identityProvider")
     */
    private $serviceProviders;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Attribute")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userAttributeMapping;

    public function __construct()
    {
      $this->serviceProviders = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function createHashId()
    {
      $this->hashId = HashIdGenerator::generate();
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

    public function getLoginURL(): ?string
    {
        return $this->loginURL;
    }

    public function setLoginURL(string $loginURL): self
    {
        $this->loginURL = $loginURL;

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

    public function getCertificate(): ?string
    {
        return $this->certificate;
    }

    //remove cert base64 headers
    public function getCertificateData(): ?string
    {
      $cert = str_replace("-----BEGIN CERTIFICATE-----", '', $this->certificate);
      $cert = str_replace("-----END CERTIFICATE-----", '', $cert);
      $cert = str_replace("\r\n", '', $cert);
      return str_replace("\r", '', $cert);
    }

    public function setCertificate(?string $certificate): self
    {
        $this->certificate = $certificate;

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
            $serviceProvider->setIdentityProvider($this);
        }

        return $this;
    }

    public function removeServiceProvider(ServiceProvider $serviceProvider): self
    {
        if ($this->serviceProviders->contains($serviceProvider)) {
            $this->serviceProviders->removeElement($serviceProvider);
            // set the owning side to null (unless already changed)
            if ($serviceProvider->getIdentityProvider() === $this) {
                $serviceProvider->setIdentityProvider(null);
            }
        }

        return $this;
    }

    public function getUserAttributeMapping(): ?Attribute
    {
        return $this->userAttributeMapping;
    }

    public function setUserAttributeMapping(?Attribute $userAttributeMapping): self
    {
        $this->userAttributeMapping = $userAttributeMapping;

        return $this;
    }
}
