<?php

namespace App\Tests\Service\Manager;

use App\Service\Manager\AttributeManager;
use App\Entity\Attribute;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AttributeManagerTest extends KernelTestCase
{
  public function setUp(): void
  {
    self::bootKernel();
  }

  public function testCreateAttribute()
  {
    // create attribute
    $attribute = new Attribute();
    $attribute->setFriendlyName('Test Attribute');
    $attribute->setAdAttribute('test_attribute');

    $attributeManager = self::$container->get(AttributeManager::class);
    $attributeManager->createAttribute($attribute);

    // fetch attribute from db
    $result = $attributeManager->getAttribute($attribute->getHashId());

    // assert is attribute
    $this->assertInstanceOf(Attribute::class, $result);
  }

  public function testUpdateAttribute()
  {
    $this->markTestSkipped('Simply flushes entities');
  }

  public function testDeleteAttribute()
  {
    // get attribute
    $attributeManager = self::$container->get(AttributeManager::class);
    $result = $attributeManager->getAttribute('nm7Od6M1jqPBY');

    // delete attribute
    $attributeManager->deleteAttribute($result);

    // assert attribute is deleted
    $this->assertTrue($result->getDeleted());
  }

  public function testGetAttribute()
  {
    // get attribute
    $attributeManager = self::$container->get(AttributeManager::class);
    $result = $attributeManager->getAttribute('nm7Od6M1jqPBY');

    // assert is attribute
    $this->assertInstanceOf(Attribute::class, $result);
  }

  public function testGetAttributes()
  {
    // get attributes
    $attributeManager = self::$container->get(AttributeManager::class);
    $result = $attributeManager->getAttributes();

    // asert attributes returned
    $this->assertIsArray($result);
  }
}
