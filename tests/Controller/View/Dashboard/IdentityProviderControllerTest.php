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
      'identity_provider[certificate]' => 'MIIC1TCCAb2gAwIBAgIJALaciWBDG1UvMA0GCSqGSIb3DQEBBQUAMBoxGDAWBgNVBAMTD3d3dy5leGFtcGxlLmNvbTAeFw0yMjAxMjAxNDUwMzVaFw0zMjAxMTgxNDUwMzVaMBoxGDAWBgNVBAMTD3d3dy5leGFtcGxlLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALBmSuUmYXvpcb/HZGtTQFN9c0uPXcdHRetdruE1QlML+3/xCodZIc3w3bCGSFk82JAvRq6vkRxUuh0JelM18LKsf6QaIz8njvdenau67H/BgoOiLd4BOZPN/Ux5SoE+f5uyt20R6L0w2pq/PE6rh9Wh9BPPPEK4OCOHZ7/ZrYjALIYYU78Hi2WDn5vD5xog60zgs9QdmK9dDGRJDh3vKIjcDFuPnuF/mKPfsi9DiaFk0RL4ba7z43r/Utgo4QPF6EuQph22VAWeCZX5YjDnAXreiAOwjxCBYPdQDSpSbcjEKJWqmgpNBSfh9zbvautTi1BQSWCsdpFFfeCiNmlO3iECAwEAAaMeMBwwGgYDVR0RBBMwEYIPd3d3LmV4YW1wbGUuY29tMA0GCSqGSIb3DQEBBQUAA4IBAQBKuP6lEt/ez0YVWEPdlEIXa/smxFJ39JXH9JsowCfPy1jGO/U1132FHV2gIkD4QCR5eQB2K9MTIGdDLOsVz1tpTw/PbMfWJGyONHZyHo/XpBpnepHEbqIohIDErQcxutfg4n+xiXcYmXmOvBY8ksrGXO0rV33JJVjmhEWb6NJ5TVaezNzLUZnie7CB6xZRWCjqos4Joj3bmhJFqayI1Tg6J+BIWSf0roKmdlhO8ZUKcEqF45tzjzT7UXYVRFtKbFilrCnXrmkidlKJOHeQmTB06atz3v4wAhHta2Sl3geZf80/4JAPGCp3hpbw1DqCLI9phXrqiiI5NDk3CSgIXbbX'
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
      'identity_provider[certificate]' => 'MIIC1TCCAb2gAwIBAgIJALaciWBDG1UvMA0GCSqGSIb3DQEBBQUAMBoxGDAWBgNVBAMTD3d3dy5leGFtcGxlLmNvbTAeFw0yMjAxMjAxNDUwMzVaFw0zMjAxMTgxNDUwMzVaMBoxGDAWBgNVBAMTD3d3dy5leGFtcGxlLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALBmSuUmYXvpcb/HZGtTQFN9c0uPXcdHRetdruE1QlML+3/xCodZIc3w3bCGSFk82JAvRq6vkRxUuh0JelM18LKsf6QaIz8njvdenau67H/BgoOiLd4BOZPN/Ux5SoE+f5uyt20R6L0w2pq/PE6rh9Wh9BPPPEK4OCOHZ7/ZrYjALIYYU78Hi2WDn5vD5xog60zgs9QdmK9dDGRJDh3vKIjcDFuPnuF/mKPfsi9DiaFk0RL4ba7z43r/Utgo4QPF6EuQph22VAWeCZX5YjDnAXreiAOwjxCBYPdQDSpSbcjEKJWqmgpNBSfh9zbvautTi1BQSWCsdpFFfeCiNmlO3iECAwEAAaMeMBwwGgYDVR0RBBMwEYIPd3d3LmV4YW1wbGUuY29tMA0GCSqGSIb3DQEBBQUAA4IBAQBKuP6lEt/ez0YVWEPdlEIXa/smxFJ39JXH9JsowCfPy1jGO/U1132FHV2gIkD4QCR5eQB2K9MTIGdDLOsVz1tpTw/PbMfWJGyONHZyHo/XpBpnepHEbqIohIDErQcxutfg4n+xiXcYmXmOvBY8ksrGXO0rV33JJVjmhEWb6NJ5TVaezNzLUZnie7CB6xZRWCjqos4Joj3bmhJFqayI1Tg6J+BIWSf0roKmdlhO8ZUKcEqF45tzjzT7UXYVRFtKbFilrCnXrmkidlKJOHeQmTB06atz3v4wAhHta2Sl3geZf80/4JAPGCp3hpbw1DqCLI9phXrqiiI5NDk3CSgIXbbX'
    ]);

    // assert valid response to form submit
    $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

    // get identity provider to validate changes
    $idpManager = self::getContainer()->get(IdentityProviderFactory::class);
    $idp = $idpManager->getIdentityProvider('bJbj6kzj1vMEn');

    // assert changes
    $this->assertEquals($idp->getName(), 'Demo IDP 2');
    $this->assertEquals($idp->getIdentifier(), 'urn:google.com');
    $this->assertEquals($idp->getLoginURL(), 'https://login.google.com/saml2');
    $this->assertEquals($idp->getUserAttributeMapping()->getHashId(), 'VVEZmx44GBqmG');
    $this->assertEquals($idp->getCertificate(), 'MIIC1TCCAb2gAwIBAgIJALaciWBDG1UvMA0GCSqGSIb3DQEBBQUAMBoxGDAWBgNVBAMTD3d3dy5leGFtcGxlLmNvbTAeFw0yMjAxMjAxNDUwMzVaFw0zMjAxMTgxNDUwMzVaMBoxGDAWBgNVBAMTD3d3dy5leGFtcGxlLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALBmSuUmYXvpcb/HZGtTQFN9c0uPXcdHRetdruE1QlML+3/xCodZIc3w3bCGSFk82JAvRq6vkRxUuh0JelM18LKsf6QaIz8njvdenau67H/BgoOiLd4BOZPN/Ux5SoE+f5uyt20R6L0w2pq/PE6rh9Wh9BPPPEK4OCOHZ7/ZrYjALIYYU78Hi2WDn5vD5xog60zgs9QdmK9dDGRJDh3vKIjcDFuPnuF/mKPfsi9DiaFk0RL4ba7z43r/Utgo4QPF6EuQph22VAWeCZX5YjDnAXreiAOwjxCBYPdQDSpSbcjEKJWqmgpNBSfh9zbvautTi1BQSWCsdpFFfeCiNmlO3iECAwEAAaMeMBwwGgYDVR0RBBMwEYIPd3d3LmV4YW1wbGUuY29tMA0GCSqGSIb3DQEBBQUAA4IBAQBKuP6lEt/ez0YVWEPdlEIXa/smxFJ39JXH9JsowCfPy1jGO/U1132FHV2gIkD4QCR5eQB2K9MTIGdDLOsVz1tpTw/PbMfWJGyONHZyHo/XpBpnepHEbqIohIDErQcxutfg4n+xiXcYmXmOvBY8ksrGXO0rV33JJVjmhEWb6NJ5TVaezNzLUZnie7CB6xZRWCjqos4Joj3bmhJFqayI1Tg6J+BIWSf0roKmdlhO8ZUKcEqF45tzjzT7UXYVRFtKbFilrCnXrmkidlKJOHeQmTB06atz3v4wAhHta2Sl3geZf80/4JAPGCp3hpbw1DqCLI9phXrqiiI5NDk3CSgIXbbX');
  }
}
