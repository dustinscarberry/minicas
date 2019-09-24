<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttributeMappingRepository")
 */
class AttributeMapping
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ServiceProvider", inversedBy="attributeMappings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Attribute")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adAttribute;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $transformation;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getService(): ?ServiceProvider
    {
        return $this->service;
    }

    public function setService(?ServiceProvider $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getAdAttribute(): ?Attribute
    {
        return $this->adAttribute;
    }

    public function setAdAttribute(?Attribute $adAttribute): self
    {
        $this->adAttribute = $adAttribute;

        return $this;
    }

    public function getTransformation(): ?string
    {
        return $this->transformation;
    }

    public function setTransformation(?string $transformation): self
    {
        $this->transformation = $transformation;

        return $this;
    }
}
