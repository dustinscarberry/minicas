<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\IdentityProvider;

class IdentityProviderFactory
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function createIdentityProvider(IdentityProvider $identityProvider)
  {
    $this->em->persist($identityProvider);
    $this->em->flush();
  }

  public function updateIdentityProvider()
  {
    // just flush entity already mapped to form :)
    $this->em->flush();
  }

  public function deleteIdentityProvider(IdentityProvider $identityProvider)
  {
    $identityProvider->setDeleted(true);
    $this->em->flush();
  }

  public function getIdentityProvider($hashId)
  {
    return $this->em
      ->getRepository(IdentityProvider::class)
      ->findByHashId($hashId);
  }

  public function getIdentityProviders()
  {
    return $this->em
      ->getRepository(IdentityProvider::class)
      ->findAllNotDeleted();
  }
}
