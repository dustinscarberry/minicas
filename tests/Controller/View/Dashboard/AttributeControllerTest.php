<?php

namespace App\Tests\Controller\View\Dashboard;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Factory\AttributeFactory;
use App\Tests\UserAuthTrait;

class AttributeControllerTest extends WebTestCase
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
    $this->client->request('GET', '/dashboard/attributes');

    // assert valid response
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
  }

  public function testAdd()
  {
    // log test user in
    $this->loginUser('demo', $this->client);

    // make request
    $this->client->request('GET', '/dashboard/attributes/add');

    // assert page loads
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    // submit form to add attribute
    $this->client->submitForm('Add Attribute', [
      'attribute[friendlyName]' => 'Demo Attribute',
      'attribute[adAttribute]' => 'demmoo_attribute'
    ]);

    // assert valid response to form submit
    $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
  }

  public function testEdit()
  {
    // log test user in
    $this->loginUser('demo', $this->client);

    // make request
    $this->client->request('GET', '/dashboard/attributes/VVEZmx44GBqmG');

    // assert page loads
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    // submit form to edit attribute
    $this->client->submitForm('Save Changes', [
      'attribute[friendlyName]' => 'Demo Attribute',
      'attribute[adAttribute]' => 'demmoo_attribute'
    ]);

    // assert valid response to form submit
    $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

    // get attribute to validate changes
    $attributeFactory = self::$container->get(AttributeFactory::class);
    $attribute = $attributeFactory->getAttribute('VVEZmx44GBqmG');

    // assert changes
    $this->assertEquals($attribute->getFriendlyName(), 'Demo Attribute');
    $this->assertEquals($attribute->getAdAttribute(), 'demmoo_attribute');
  }
}
