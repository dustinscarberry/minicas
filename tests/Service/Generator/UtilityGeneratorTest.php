<?php

namespace App\Tests\Service\Generator;

use App\Service\Generator\UtilityGenerator;
use PHPUnit\Framework\TestCase;

class UtilityGeneratorTest extends TestCase
{
  public function testCleanService()
  {
    $result = UtilityGenerator::cleanService(
      'https://google.com/?v=xxx'
    );

    // assert equals
    $this->assertEquals('google.com', $result);
  }
}
