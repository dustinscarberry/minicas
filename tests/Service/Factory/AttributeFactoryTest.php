<?php

namespace App\Tests\Service\Manager;

use App\Service\Factory\AttributeFactory;
use App\Entity\Attribute;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AttributeFactoryTest extends KernelTestCase
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

    $attributeFactory = self::$container->get(AttributeFactory::class);
    $attributeFactory->createAttribute($attribute);

    // fetch attribute from db
    $result = $attributeFactory->getAttribute($attribute->getHashId());

    // assert is attribute
    $this->assertInstanceOf(Attribute::class, $result);
  }

  public function testDeleteAttribute()
  {
    // get attribute
    $attributeFactory = self::$container->get(AttributeFactory::class);
    $result = $attributeFactory->getAttribute('VVEZmx44GBqmG');

    // delete attribute
    $attributeFactory->deleteAttribute($result);

    // assert attribute is deleted
    $this->assertTrue($result->getDeleted());
  }

  public function testGetAttribute()
  {
    // get attribute
    $attributeFactory = self::$container->get(AttributeFactory::class);
    $result = $attributeFactory->getAttribute('VVEZmx44GBqmG');

    // assert is attribute
    $this->assertInstanceOf(Attribute::class, $result);
  }

  public function testGetAttributes()
  {
    // get attributes
    $attributeFactory = self::$container->get(AttributeFactory::class);
    $result = $attributeFactory->getAttributes();

    // asert attributes returned
    $this->assertIsArray($result);
  }
}
