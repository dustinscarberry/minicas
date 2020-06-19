<?php

namespace App\Tests\Service\Generator;

use App\Service\Generator\CASGenerator;
use App\Exception\InvalidTicketException;
use App\Exception\InvalidServiceException;
use App\Exception\InvalidRequestException;
use \Exception;
use PHPUnit\Framework\TestCase;

class CASGeneratorTest extends TestCase
{
  public function testGenerateTicket()
  {
    $result = CASGenerator::generateTicket();

    // assert is string
    $this->assertIsString($result);
    // assert contains ST-
    $this->assertStringStartsWith('ST-', $result);
  }

  public function testGetTicketRedirectUrl()
  {
    $result = CASGenerator::getTicketRedirectUrl('https://example.com/login', 'ST-xxxxxxxxxxxx');

    // assert equals
    $this->assertEquals('https://example.com/login?ticket=ST-xxxxxxxxxxxx', $result);
  }

  public function testGetErrorResponseWithInvalidTicketException()
  {
    $result = CASGenerator::getErrorResponse(new InvalidTicketException('Invalid Ticket'));

    $expected = '<cas:serviceResponse xmlns:cas="http://www.yale.edu/tp/cas">';
    $expected .= '<cas:authenticationFailure code="INVALID_TICKET">Invalid Ticket</cas:authenticationFailure>';
    $expected .= '</cas:serviceResponse>';

    // assert is string
    $this->assertIsString($result);
    // assert xml response
    $this->assertEquals($expected, $result);
  }

  public function testGetErrorResponseWithInvalidServiceException()
  {
    $result = CASGenerator::getErrorResponse(new InvalidServiceException('Invalid Service'));

    $expected = '<cas:serviceResponse xmlns:cas="http://www.yale.edu/tp/cas">';
    $expected .= '<cas:authenticationFailure code="INVALID_SERVICE">Invalid Service</cas:authenticationFailure>';
    $expected .= '</cas:serviceResponse>';

    // assert is string
    $this->assertIsString($result);
    // assert xml response
    $this->assertEquals($expected, $result);
  }

  public function testGetErrorResponseWithInvalidRequestException()
  {
    $result = CASGenerator::getErrorResponse(new InvalidRequestException('Invalid Request'));

    $expected = '<cas:serviceResponse xmlns:cas="http://www.yale.edu/tp/cas">';
    $expected .= '<cas:authenticationFailure code="INVALID_REQUEST">Invalid Request</cas:authenticationFailure>';
    $expected .= '</cas:serviceResponse>';

    // assert is string
    $this->assertIsString($result);
    // assert xml response
    $this->assertEquals($expected, $result);
  }

  public function testGetErrorResponseWithGenericException()
  {
    $result = CASGenerator::getErrorResponse(new Exception('Internal Error'));

    $expected = '<cas:serviceResponse xmlns:cas="http://www.yale.edu/tp/cas">';
    $expected .= '<cas:authenticationFailure code="INTERNAL_ERROR">An internal error has occured</cas:authenticationFailure>';
    $expected .= '</cas:serviceResponse>';

    // assert is string
    $this->assertIsString($result);
    // assert xml response
    $this->assertEquals($expected, $result);
  }
}
