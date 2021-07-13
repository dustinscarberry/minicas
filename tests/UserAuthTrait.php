<?php

namespace App\Tests;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
//use App\Entity\User;
use App\Repository\UserRepository;

trait UserAuthTrait
{
  private function loginUser(string $username, $client)
  {
    //$client = static::createClient();
    $userRepository = static::$container->get(UserRepository::class);

    // retrieve the test user
    $testUser = $userRepository->findOneBy(['username' => $username]);

    // simulate $testUser being logged in
    $client->loginUser($testUser);



  //  $session = self::$container->get('session');

    // fetch user
  //  $user = self::$container->get('doctrine')->getRepository(User::class)->findOneBy(['username' => $username]);

//    $firewallName = 'main';
    // if you don't define multiple connected firewalls, the context defaults to the firewall name
    // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
//    $firewallContext = 'main';

    // you may need to use a different token class depending on your application.
    // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
  //  $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
  //  $session->set('_security_' . $firewallContext, serialize($token));
  //  $session->save();
//
//    $cookie = new Cookie($session->getName(), $session->getId());
  //  $this->client->getCookieJar()->set($cookie);
  }
}
