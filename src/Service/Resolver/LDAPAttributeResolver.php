<?php

namespace App\Service\Resolver;

use Symfony\Component\Ldap\Ldap;
use Exception;

class LDAPAttributeResolver
{
  private $ldapHandle;
  private $ldapSearchBase;

  public function configure(
    $ldapHost = '',
    $ldapEncryption = 'ssl',
    $ldapPort = 636,
    $ldapReferrals = false,
    $ldapVersion = 3,
    $ldapUser = '',//pass dn string format
    $ldapPassword = '',
    $ldapSearchBase = ''
  )
  {
    try {
      $this->ldapHandle = Ldap::create('ext_ldap', [
        'host' => $ldapHost,
        'encryption' => $ldapEncryption,
        'port' => intval($ldapPort),
        'referrals' => $ldapReferrals == 'true' ? true : false,
        'version' => intval($ldapVersion)
      ]);

      $this->ldapHandle->bind($ldapUser, $ldapPassword);

      $this->ldapSearchBase = $ldapSearchBase;

    } catch (Exception $e) {
      throw new Exception('Error contacting the domain');
    }
  }

  //gets mapped attributes to be used for return value to service
  public function getMappedAttributes($user, $adFilter, $mappings, $userMapping = null)
  {
    //check for valid ldap handle
    if (!$this->ldapHandle)
      throw new Exception('LDAP Attribute Resolver not configured');

    //get user ldap entry
    $queryFilter = '(&(objectclass=person)(' . $adFilter . '=' . $user . '))';
    $query = $this->ldapHandle->query($this->ldapSearchBase, $queryFilter);
    $result = $query->execute();

    if (!$result)
      throw new Exception('User not found in LDAP');

    $entry = $result[0];

    //map user attributes to return
    $userAttributes = [
      'user' => '',
      'attributes' => []
    ];

    foreach ($mappings as $mapping)
    {
      $attrValue = $entry->getAttribute($mapping->getAdAttribute()->getAdAttribute());
      if ($attrValue && is_array($attrValue))
        $attrValue = reset($attrValue);

      $userAttributes['attributes'][] = (object)[
        'name' => $mapping->getName(),
        'value' => $attrValue
      ];
    }

    //map user to different ad attribute if provided
    if ($userMapping)
    {
      $userAttributes['user'] = $entry->getAttribute($userMapping);
      if ($userAttributes['user'] && is_array($userAttributes['user']))
        $userAttributes['user'] = reset($userAttributes['user']);
    }

    return $userAttributes;
  }
}
