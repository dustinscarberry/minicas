<?php

namespace App\Service\Generator;

use Symfony\Component\HttpFoundation\Cookie;
use App\Service\Resolver\LDAPAttributeResolver;

class AuthGenerator
{
  public static function createCommonAuthCookie(string $token)
  {
    return Cookie::create('commonauth', $token, 0, '/', 'minicas.dev', true, true, false, 'strict');
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
