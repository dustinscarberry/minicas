<?php

namespace App\Tests\Controller\View\Dashboard;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\UserAuthTrait;

class AccountControllerTest extends WebTestCase
{
  use UserAuthTrait;

  private $client;

  public function setUp(): void
  {
    $this->client = self::createClient();
  }

  public function testView()
  {
    // log test user in
    $this->loginUser('demo', $this->client);

    // make request
    $this->client->request('GET', '/dashboard/account');

    // assert valid response
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
  }
}
