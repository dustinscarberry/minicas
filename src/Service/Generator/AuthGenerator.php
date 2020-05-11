<?php

namespace App\Service\Generator;

use Symfony\Component\HttpFoundation\Cookie;
use App\Service\Resolver\LDAPAttributeResolver;

class AuthGenerator
{
  // create a commonauth cookie
  public static function createCommonAuthCookie(string $token)
  {
    $cookieDomain = str_replace('https://', '', $_ENV['APP_HOST']);
    $cookieDomain = str_replace('http://', '', $cookieDomain);

    return Cookie::create(
      'commonauth',
      $token,
      0,
      '/',
      $cookieDomain,
      true,
      true,
      false,
      'strict'
    );
  }

  public static function resolveAttributes(
    $authenticatedUser,
    $userFilterAttributeMapping,
    $attributeMappings,
    $userAttributeMapping
  )
  {
    $ldapAttributeResolver = new LDAPAttributeResolver();
    $ldapAttributeResolver->configure(
      $_ENV['LDAP_HOST'],
      $_ENV['LDAP_ENCRYPTION'],
      $_ENV['LDAP_PORT'],
      $_ENV['LDAP_REFERRALS'],
      $_ENV['LDAP_VERSION'],
      $_ENV['LDAP_ADMIN_USER'],
      $_ENV['LDAP_ADMIN_PASSWORD'],
      $_ENV['LDAP_SEARCH_BASE']
    );

    return $ldapAttributeResolver->getMappedAttributes(
      $authenticatedUser,
      $userFilterAttributeMapping,
      $attributeMappings,
      $userAttributeMapping
    );
  }
}
