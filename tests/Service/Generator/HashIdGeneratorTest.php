<?php

namespace App\Tests\Service\Generator;

use App\Service\Generator\HashIdGenerator;
use PHPUnit\Framework\TestCase;

class HashIdGeneratorTest extends TestCase
{
  public function testGenerate()
  {
    $result = HashIdGenerator::generate();

    // assert that return value is string
    $this->assertIsString($result);
  }
}
