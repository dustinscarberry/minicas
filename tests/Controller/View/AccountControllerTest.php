<?php

namespace App\Tests\Controller\View;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{
  public function testView()
  {
    $client = self::createClient();
    //$client->request('GET', '/dashboard/account');
    //$this->assertEquals(200, $client->getResponse()->getStatusCode());
  }
}
