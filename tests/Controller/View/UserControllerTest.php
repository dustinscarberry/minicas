<?php

namespace App\Tests\Controller\View;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\UserAuthTrait;

class UserControllerTest extends WebTestCase
{
  use UserAuthTrait;

  private $client;

  public function setUp(): void
  {
    $this->client = self::createClient();
  }

  public function testViewall()
  {
    // log test user in
    $this->loginUser('demo');

    // make request
    $this->client->request('GET', '/dashboard/users');

    // assert valid response
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
  }

  public function testAdd()
  {
    // log test user in
    $this->loginUser('demo');

    // make request
    $this->client->request('GET', '/dashboard/users/add');

    // assert page loads
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    // submit form to add user
    $this->client->submitForm('Add User', [
      'user[username]' => 'demo2',
      'user[firstName]' => 'Demo 2',
      'user[lastName]' => 'Demo 2',
      'user[email]' => 'demo2@example.com',
      'user[password][first]' => 'demo',
      'user[password][second]' => 'demo'
    ]);

    // assert valid response to form submit
    $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
  }
}
