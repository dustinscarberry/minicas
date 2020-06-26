<?php

namespace App\Tests;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Entity\User;

trait UserAuthTrait
{
  private function loginUser(string $username)
  {
    $session = self::$container->get('session');

    // fetch user
    $user = self::$container->get('doctrine')->getRepository(User::class)->findOneBy(['username' => $username]);

    $firewallName = 'main';
    // if you don't define multiple connected firewalls, the context defaults to the firewall name
    // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
    $firewallContext = 'main';

    // you may need to use a different token class depending on your application.
    // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
    $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
    $session->set('_security_' . $firewallContext, serialize($token));
    $session->save();

    $cookie = new Cookie($session->getName(), $session->getId());
    $this->client->getCookieJar()->set($cookie);
  }
}
