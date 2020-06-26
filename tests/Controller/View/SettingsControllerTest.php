<?php

namespace App\Tests\Controller\View;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\UserAuthTrait;

class SettingsControllerTest extends WebTestCase
{
  use UserAuthTrait;

  private $client;

  public function setUp(): void
  {
    $this->client = self::createClient();
  }

  public function testUpdate()
  {
    // log test user in
    $this->loginUser('demo');

    // make request
    $this->client->request('GET', '/dashboard/settings');

    // assert page loads
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    // submit form to save settings
    $this->client->submitForm('Save Changes', [
      'setting[siteName]' => 'DAS Demo',
      'setting[sessionTimeout]' => 600,
      'setting[casTicketTimeout]' => 10,
      'setting[autoDeleteExpiredSessions]' => 0,
      'setting[siteTimezone]' => 'America/New_York',
      'setting[locale]' => 'en',
      'setting[language]' => 'en'
    ]);

    // assert valid response to form submit
    $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
  }
}
