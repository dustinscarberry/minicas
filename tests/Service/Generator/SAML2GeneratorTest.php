<?php

namespace App\Tests\Service\Generator;

use App\Service\Generator\SAML2Generator;
use PHPUnit\Framework\TestCase;

class SAML2GeneratorTest extends TestCase
{
  public function testGetRequestURL()
  {
    $result = SAML2Generator::getRequestURL(
      'id_xxxxx',
      'https://example.com/saml2',
      'urn:example.com',
      'https://example.com'
    );

    // assert starts with host and params
    $this->assertStringStartsWith('https://example.com/saml2?SAMLRequest=', $result);
  }

  public function testGenerateID()
  {
    $result = SAML2Generator::generateID();

    // assert is string
    $this->assertIsString($result);
    // assert starts with id_
    $this->assertStringStartsWith('id_', $result);
  }
}
