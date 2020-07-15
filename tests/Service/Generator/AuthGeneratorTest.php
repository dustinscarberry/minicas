<?php

namespace App\Tests\Service\Generator;

use App\Service\Generator\AuthGenerator;
use Symfony\Component\HttpFoundation\Cookie;
use PHPUnit\Framework\TestCase;

class AuthGeneratorTest extends TestCase
{
  public function testCreateCommonAuthCookie()
  {
    $result = AuthGenerator::createCommonAuthCookie('xxx');

    // assert is cookie class
    $this->assertInstanceOf(Cookie::class, $result);
    // assert cookie properties
    $this->assertEquals('commonauth', $result->getName());
    $this->assertEquals('xxx', $result->getValue());
  }
}
