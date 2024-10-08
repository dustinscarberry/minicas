<?php

namespace App\Tests\Controller\View\Dashboard;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginControllerTest extends WebTestCase
{
  private $client;

  public function setUp(): void
  {
    $this->client = self::createClient();
  }

  public function testLogin()
  {
    // make request
    $this->client->request('GET', '/dashboard/login');

    // assert valid response
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
  }
}
