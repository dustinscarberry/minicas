<?php

namespace App\Tests\Controller\View\Dashboard;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\UserAuthTrait;

class InvalidServiceControllerTest extends WebTestCase
{
  use UserAuthTrait;

  private $client;

  public function setUp(): void
  {
    $this->client = self::createClient();
  }

  public function testViewAll()
  {
    // log test user in
    $this->loginUser('demo');

    // make request
    $this->client->request('GET', '/dashboard/invalidservices');

    // assert valid response
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
  }
}
