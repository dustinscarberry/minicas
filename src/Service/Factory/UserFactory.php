<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

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

  public function createUser($user)
  {
    $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
    $user->setPassword($encodedPassword);
    $user->setRoles(['ROLE_ADMIN']);
    $this->em->persist($user);
    $this->em->flush();
  }

  public function updateUser($user, $newPassword = null)
  {
    //change password if new provided
    if ($newPassword)
      $user->setPassword($this->passwordEncoder->encodePassword($user, $newPassword));

    //flush user object
    $this->em->flush();
  }

  public function deleteUser($user)
  {
    $this->em->remove($user);
    $this->em->flush();
  }

  public function getUser($hashId)
  {
    return $this->em
      ->getRepository(User::class)
      ->findByHashId($hashId);
  }

  public function getUsers()
  {
    return $this->em
      ->getRepository(User::class)
      ->findAll();
  }
}
