<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Service\Generator\HashIdGenerator;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Index(name: 'user_hashid_idx', columns: ['hash_id'])]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 25)]
  private $hashId;

  #[ORM\Column(type: 'string', length: 180, unique: true)]
  private $username;

  #[ORM\Column(type: 'json')]
  private $roles = [];

  /**
   * @var string The hashed password
   */
  #[ORM\Column(type: 'string')]
  private $password;

  #[ORM\Column(type: 'string', length: 255)]
  private $email;

  #[ORM\Column(type: 'string', length: 255)]
  private $firstName;

  #[ORM\Column(type: 'string', length: 255)]
  private $lastName;

  #[ORM\Column(type: 'integer')]
  private $created;

  #[ORM\Column(type: 'integer')]
  private $updated;

  #[ORM\Column(nullable: true)]
  private ?int $lastLogin = null;

  #[ORM\Column(nullable: true)]
  private ?int $lastFailedLogin = null;

  #[ORM\Column(nullable: true)]
  private ?int $failedLoginCount = null;

  #[ORM\PrePersist]
  #[ORM\PreUpdate]
  public function updateTimestamps()
  {
    $currentTime = time();
    $this->setUpdated($currentTime);

    if ($this->getCreated() == null)
      $this->setCreated($currentTime);
  }

  #[ORM\PrePersist]
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

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUsername(): string
  {
    return (string) $this->username;
  }

  public function setUsername(string $username): self
  {
    $this->username = $username;
    return $this;
  }

  public function getUserIdentifier(): string
  {
    return $this->username;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): self
  {
    $this->roles = $roles;
    return $this;
  }

  /**
   * @see UserInterface
   */
  public function getPassword(): ?string
  {
    return (string) $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;
    return $this;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;
    return $this;
  }

  public function getFirstName(): ?string
  {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): self
  {
    $this->firstName = $firstName;
    return $this;
  }

  public function getLastName(): ?string
  {
    return $this->lastName;
  }

  public function setLastName(string $lastName): self
  {
    $this->lastName = $lastName;
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
   * @see UserInterface
   */
  public function getSalt(): ?string
  {
    // not needed when using the "bcrypt" algorithm in security.yaml
    return null;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  public function getGravatar()
  {
    $hash = md5(strtolower(trim($this->getEmail())));
    return 'https://www.gravatar.com/avatar/' . $hash . '.jpg?d=retro';
  }

  public function getFullName()
  {
    return $this->getFirstName() . ' ' . $this->getLastName();
  }

  public function getLastLogin(): ?int
  {
      return $this->lastLogin;
  }

  public function setLastLogin(int $lastLogin): static
  {
      $this->lastLogin = $lastLogin;

      return $this;
  }

  public function getLastFailedLogin(): ?int
  {
      return $this->lastFailedLogin;
  }

  public function setLastFailedLogin(?int $lastFailedLogin): static
  {
      $this->lastFailedLogin = $lastFailedLogin;

      return $this;
  }

  public function getFailedLoginCount(): ?int
  {
      return $this->failedLoginCount;
  }

  public function setFailedLoginCount(?int $failedLoginCount): static
  {
      $this->failedLoginCount = $failedLoginCount;

      return $this;
  }
}
