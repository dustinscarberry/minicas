<?php

namespace App\Tests\Controller\View;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Manager\ServiceProviderManager;
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
    $crawler = $this->client->request('GET', '/dashboard/serviceproviders/add');

    // assert page loads
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    // submit form to add service provider
    $form = $crawler->selectButton('Create')->form();
    $formValues = $form->getPhpValues();

    //$formValues['service_provider']['enabled'] = 1; default boolean
    $formValues['service_provider']['name'] = 'Demo Service Provider';
    $formValues['service_provider']['type'] = 'cas';
    $formValues['service_provider']['identifier'] = 'demoprovider';
    //$formValues['service_provider']['domainIdentifier'] = 1; default boolean
    $formValues['service_provider']['identityProvider'] = 'bJbj6kzj1vMEn';
    $formValues['service_provider']['attributeMappings'][0]['name'] = 'name';
    $formValues['service_provider']['attributeMappings'][0]['adAttribute'] = '47Dg5J3K3o0Kj';
    $formValues['service_provider']['attributeMappings'][0]['transformation'] = '';

    $this->client->request($form->getMethod(), $form->getUri(), $formValues, $form->getPhpFiles());

    // assert valid response to form submit
    $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
  }

  public function testEdit()
  {
    // log test user in
    $this->loginUser('demo');

    // make request
    $crawler = $this->client->request('GET', '/dashboard/serviceproviders/w2PwYJXRW4nqZ');

    // assert page loads
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    // submit form to edit service provider
    $form = $crawler->selectButton('Save')->form();
    $formValues = $form->getPhpValues();

    unset($formValues['service_provider']['enabled']);
    $formValues['service_provider']['name'] = 'Demo Service Provider Updated';
    $formValues['service_provider']['type'] = 'cas';
    $formValues['service_provider']['identifier'] = 'demoprovidermod';
    $formValues['service_provider']['domainIdentifier'] = 1;
    $formValues['service_provider']['identityProvider'] = 'bJbj6kzj1vMEn';
    unset($formValues['service_provider']['attributeMappings']);

    $this->client->request($form->getMethod(), $form->getUri(), $formValues, $form->getPhpFiles());

    // assert valid response to form submit
    $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

    // get service provider to validate changes
    $serviceProviderManager = self::$container->get(ServiceProviderManager::class);
    $serviceProvider = $serviceProviderManager->getServiceProvider('w2PwYJXRW4nqZ');

    // assert changes
    $this->assertEquals($serviceProvider->getName(), 'Demo Service Provider Updated');
    $this->assertNotTrue($serviceProvider->getEnabled());
    $this->assertEquals($serviceProvider->getIdentifier(), 'demoprovidermod');
    $this->assertTrue($serviceProvider->getDomainIdentifier());
    $this->assertEmpty($serviceProvider->getAttributeMappings());
  }
}
