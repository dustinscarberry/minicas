<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Attribute;

/**
 * Factory class to create, edit, update, and fetch attributes
 *
 * @package MiniCAS
 * @author Dustin Scarberry <bitnsbytes1001@gmail.com>
 */
class AttributeFactory
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  /**
   * Create attribute
   *
   * @param Attribute $attribute
   */
  public function createAttribute(Attribute $attribute)
  {
    $this->em->persist($attribute);
    $this->em->flush();
  }

  /**
   * Update attribute
   */
  public function updateAttribute()
  {
    $this->em->flush();
  }

  /**
   * Delete attribute
   *
   * @param Attribute $attribute
   */
  public function deleteAttribute(Attribute $attribute)
  {
    $attribute->setDeleted(true);
    $this->em->flush();
  }

  /**
   * Get attribute by hashId
   *
   * @param string $hashId
   * @return Attribute|null
   */
  public function getAttribute(string $hashId)
  {
    return $this->em
      ->getRepository(Attribute::class)
      ->findByHashId($hashId);
  }

  /**
   * Get all attributes not deleted
   *
   * @return Attribute[]|null
   */
  public function getAttributes()
  {
    return $this->em
      ->getRepository(Attribute::class)
      ->findAllNotDeleted();
  }
}
