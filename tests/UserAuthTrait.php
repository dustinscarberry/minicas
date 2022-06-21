<?php

namespace App\Tests;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
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
  }
}
