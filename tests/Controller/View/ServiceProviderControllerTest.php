<?php

namespace App\Tests\Controller\View;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\UserAuthTrait;

class ServiceProviderControllerTest extends WebTestCase
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
    $this->loginUser('demo');

    // make request
    $this->client->request('GET', '/dashboard/serviceproviders');

    // assert valid response
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
  }

  public function testAdd()
  {
    // log test user in
    $this->loginUser('demo');

    // make request
    $this->client->request('GET', '/dashboard/serviceproviders/add');

    // assert page loads
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    // get entity data for relationships
    /*

    // submit form to add service provider
    $this->client->submitForm('Create', [
      'service_provider[enabled]' => 1,
      'service_provider[name]' => 'Demo Service Provider',
      'service_provider[type]' => 'cas',
      'service_provider[identifier]' => 'demoprovider',
      'service_provider[domainIdentifier]' => 0,
      'service_provider[identityProvider]' => 'xxxx',
      'service_provider[attributeMappings][0][name]' => 'name',
      'service_provider[attributeMappings][0][adAttribute]' => 'xxx'
      'service_provider[attributeMappings][0][transformation]' => 'uppercase'
    ]);

    // assert valid response to form submit
    $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());*/
  }

  public function testEdit()
  {

  }
}
