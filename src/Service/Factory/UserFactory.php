<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

/**
 * Create, update, delete and fetch users
 *
 * @package DAS
 * @author Dustin Scarberry <dustin@codeclouds.net>
 */
class UserFactory
{
  private $em;
  private $security;
  private $passwordEncoder;

  public function __construct(
    EntityManagerInterface $em,
    UserPasswordEncoderInterface $passwordEncoder
  )
  {
    $this->em = $em;
    $this->passwordEncoder = $passwordEncoder;
  }

  /**
   * Create user
   *
   * @param User $user
   * @return User
   */
  public function createUser(User $user): User
  {
    $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
    $user->setPassword($encodedPassword);
    $user->setRoles(['ROLE_ADMIN']);
    $this->em->persist($user);
    $this->em->flush();

    return $user;
  }

  /**
   * Update user
   *
   * @param User $user
   * @param string|null $password
   * @return User
   */
  public function updateUser(User $user, string $newPassword = null): User
  {
    // change password if provided
    if ($newPassword)
      $user->setPassword($this->passwordEncoder->encodePassword($user, $newPassword));

    // flush user object
    $this->em->flush();

    return $user;
  }

  /**
   * Delete user
   *
   * @param User $user
   */
  public function deleteUser(User $user)
  {
    $this->em->remove($user);
    $this->em->flush();
  }

  /**
   * Fetch user by hashId
   *
   * @param string $hashId
   * @return User|null
   */
  public function getUser(string $hashId): ?User
  {
    return $this->em
      ->getRepository(User::class)
      ->findByHashId($hashId);
  }

  /**
   * Fetch all users
   *
   * @return User[]|null
   */
  public function getUsers()
  {
    return $this->em
      ->getRepository(User::class)
      ->findAll();
  }
}
