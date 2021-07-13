<?php

namespace App\Tests\Controller\View\Dashboard;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Factory\IdentityProviderFactory;
use App\Tests\UserAuthTrait;

class IdentityProviderControllerTest extends WebTestCase
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
    $this->loginUser('demo', $this->client);

    // make request
    $this->client->request('GET', '/dashboard/identityproviders');

    // assert valid response
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
  }

  public function testAdd()
  {
    // log test user in
    $this->loginUser('demo', $this->client);

    // make request
    $crawler = $this->client->request('GET', '/dashboard/identityproviders/add');

    // assert page loads
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    // submit form to add identity provider
    $this->client->submitForm('Create', [
      'identity_provider[name]' => 'Demo IDP 2',
      'identity_provider[type]' => 'saml2',
      'identity_provider[identifier]' => 'urn:google.com',
      'identity_provider[loginURL]' => 'https://login.google.com/saml2',
      'identity_provider[userAttributeMapping]' => 'VVEZmx44GBqmG',
      'identity_provider[certificate]' => 'RAs_JObaf90jlmzwhlHc7hXtMQztVfbc6zO5EcSsg2gxxxxxxx'
    ]);

    // assert valid response to form submit
    $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
  }

  public function testEdit()
  {
    // log test user in
    $this->loginUser('demo', $this->client);

    // make request
    $crawler = $this->client->request('GET', '/dashboard/identityproviders/bJbj6kzj1vMEn');

    // assert page loads
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    // submit form to edit identity provider
    $this->client->submitForm('Save', [
      'identity_provider[name]' => 'Demo IDP 2',
      'identity_provider[type]' => 'saml2',
      'identity_provider[identifier]' => 'urn:google.com',
      'identity_provider[loginURL]' => 'https://login.google.com/saml2',
      'identity_provider[userAttributeMapping]' => 'VVEZmx44GBqmG',
      'identity_provider[certificate]' => 'RAs_JObaf90jlmzwhlHc7hXtMQztVfbc6zO5EcSsg2gxxxxxxx'
    ]);

    // assert valid response to form submit
    $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

    // get identity provider to validate changes
    $idpManager = self::$container->get(IdentityProviderFactory::class);
    $idp = $idpManager->getIdentityProvider('bJbj6kzj1vMEn');

    // assert changes
    $this->assertEquals($idp->getName(), 'Demo IDP 2');
    $this->assertEquals($idp->getIdentifier(), 'urn:google.com');
    $this->assertEquals($idp->getLoginURL(), 'https://login.google.com/saml2');
    $this->assertEquals($idp->getUserAttributeMapping()->getHashId(), 'VVEZmx44GBqmG');
    $this->assertEquals($idp->getCertificate(), 'RAs_JObaf90jlmzwhlHc7hXtMQztVfbc6zO5EcSsg2gxxxxxxx');
  }
}
