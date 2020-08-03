<?php

namespace App\Service\Generator;

use Symfony\Component\HttpFoundation\Cookie;
use App\Service\Provider\LDAPAttributeProvider;

/**
 * Authentication helper
 *
 * @package DAS
 * @author Dustin Scarberry <dustin@codeclouds.net>
 */
class AuthGenerator
{
  /**
   * Return commonauth cookie
   *
   * @param string $token
   * @return Cookie
   */
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
      'lax'
    );
  }

  public static function resolveAttributes(
    $authenticatedUser,
    $userFilterAttributeMapping,
    $attributeMappings,
    $userAttributeMapping
  )
  {
    $ldapAttributeProvider = new LDAPAttributeProvider();
    $ldapAttributeProvider->configure(
      $_ENV['LDAP_HOST'],
      $_ENV['LDAP_ENCRYPTION'],
      $_ENV['LDAP_PORT'],
      $_ENV['LDAP_REFERRALS'],
      $_ENV['LDAP_VERSION'],
      $_ENV['LDAP_ADMIN_USER'],
      $_ENV['LDAP_ADMIN_PASSWORD'],
      $_ENV['LDAP_SEARCH_BASE']
    );

    return $ldapAttributeProvider->getMappedAttributes(
      $authenticatedUser,
      $userFilterAttributeMapping,
      $attributeMappings,
      $userAttributeMapping
    );
  }
}
