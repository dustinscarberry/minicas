<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ServiceCategory;

/**
 * Factory class to create, edit, update, and fetch service categories
 *
 * @package MiniCAS
 * @author Dustin Scarberry
 */
class ServiceCategoryFactory
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  /**
   * Create category
   *
   * @param ServiceCategory $serviceCategory
   */
  public function createServiceCategory(ServiceCategory $serviceCategory)
  {
    $this->em->persist($serviceCategory);
    $this->em->flush();
  }

  /**
   * Update service category
   */
  public function updateServiceCategory()
  {
    $this->em->flush();
  }

  /**
   * Delete service category
   *
   * @param ServiceCategory $serviceCategory
   */
  public function deleteServiceCategory(ServiceCategory $serviceCategory)
  {
    $serviceCategory->setDeleted(true);
    $this->em->flush();
  }

  /**
   * Get service category by hashId
   *
   * @param string $hashId
   * @return ServiceCategory|null
   */
  public function getServiceCategory(string $hashId)
  {
    return $this->em
      ->getRepository(ServiceCategory::class)
      ->findByHashId($hashId);
  }

  /**
   * Get all service categories not deleted
   *
   * @return ServiceCategory[]|null
   */
  public function getServiceCategories()
  {
    return $this->em
      ->getRepository(ServiceCategory::class)
      ->findAllNotDeleted();
  }
}
