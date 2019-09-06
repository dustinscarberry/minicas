<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Attribute;

class AttributeManager
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function createAttribute(Attribute $attribute)
  {
    $this->em->persist($attribute);
    $this->em->flush();
  }

  public function updateAttribute()
  {
    $this->em->flush();
  }

  public function deleteAttribute(Attribute $attribute)
  {
    $attribute->setDeleted(true);
    $this->em->flush();
  }

  public function getAttribute(string $hashId)
  {
    return $this->em
      ->getRepository(Attribute::class)
      ->findByHashId($hashId);
  }

  public function getAttributes()
  {
    return $this->em
      ->getRepository(Attribute::class)
      ->findAllNotDeleted();
  }
}
