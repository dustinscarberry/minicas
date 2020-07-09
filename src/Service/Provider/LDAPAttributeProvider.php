<?php

namespace App\Service\Provider;

use Symfony\Component\Ldap\Ldap;
use Exception;

class LDAPAttributeProvider
{
  private $ldapHandle;
  private $ldapSearchBase;

  public function configure(
    $ldapHost = '',
    $ldapEncryption = 'ssl',
    $ldapPort = 636,
    $ldapReferrals = false,
    $ldapVersion = 3,
    $ldapUser = '', // pass dn string format
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
    //get user entry
    $entry = $this->getLdapResult($adFilter, $user, 'person');

    //map user attributes to return
    $userAttributes = [
      'user' => '',
      'attributes' => []
    ];

    foreach ($mappings as $mapping)
    {
      //get attribute value
      $attrValue = $entry->getAttribute($mapping->getAdAttribute()->getAdAttribute());

      if ($attrValue && is_array($attrValue) && count($attrValue) == 1)
        $attrValue = reset($attrValue);

      //apply transformations
      $attrValue = $this->transformAttribute($attrValue, $mapping->getTransformation());

      //add mapped attributes to array
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

    // return attributes
    return $userAttributes;
  }

  // get ldap result by search filter
  private function getLdapResult($filterAttribute, $filterValue, $objectType = 'person')
  {
    //check for valid ldap handle
    if (!$this->ldapHandle)
      throw new Exception('LDAP Attribute Resolver not configured');

    //get ldap entry
    $queryFilter = '(&(objectclass=' . $objectType . ')(' . $filterAttribute . '=' . $filterValue . '))';
    $query = $this->ldapHandle->query($this->ldapSearchBase, $queryFilter);
    $result = $query->execute();

    if (!$result)
      throw new Exception('Result not found in LDAP');

    return $result[0];
  }

  //get ldap result by fully qualified dn
  private function getFullyQualifiedLdapResult($dn, $objectType = 'group')
  {
    //check for valid ldap handle
    if (!$this->ldapHandle)
      throw new Exception('LDAP Attribute Resolver not configured');

    //get ldap entry
    $queryFilter = '(objectclass=' . $objectType . ')';
    $query = $this->ldapHandle->query($dn, $queryFilter);
    $result = $query->execute();

    return $result[0];
  }

  private function transformAttribute($attrValue, $transform = null)
  {
    if ($transform == 'expandedgroups'
      || $transform == 'simplifiedexpandedgroups'
    )
    {
      // expand groups
      $attrValue = $this->expandGroups($attrValue);

      // filter out duplicates
      $attrValue = array_unique($attrValue);

      // reorganize array to fill in missing indexes above
      $attrValue = array_values($attrValue);

      if ($transform == 'simplifiedexpandedgroups')
      {
        //extractCNs
        return $this->extractCNs($attrValue);
      }

      return $attrValue;
    }
    else if ($transform == 'simplifiedgroups')
    {
      //extractCNs
      return $this->extractCNs($attrValue);
    }
    else if ($transform == 'extractmailprefix')
    {
      if (is_array($attrValue))
      {
        array_walk($attrValue, function(&$item, $key) {
          $item = substr($item, 0, strpos($item, '@'));
        });

        return $attrValue;
      }
      else
        return substr($attrValue, 0, strpos($attrValue, '@'));
    }
    else if ($transform == 'uppercase')
    {
      if (is_array($attrValue))
      {
        array_walk($attrValue, function(&$item, $key) {
          $item = strtoupper($item);
        });

        return $attrValue;
      }
      else
        return strtoupper($attrValue);
    }
    else if ($transform == 'lowercase')
    {
      if (is_array($attrValue))
      {
        array_walk($attrValue, function(&$item, $key) {
          $item = strtolower($item);
        });

        return $attrValue;
      }
      else
        return strtolower($attrValue);
    }
    else
      return $attrValue;
  }

  private function expandGroups($groupDNs)
  {
    $results = [];

    foreach ($groupDNs as $groupDN)
    {
      // add group dn to final results
      $results[] = $groupDN;

      // lookup group info
      $groupInfo = $this->getFullyQualifiedLdapResult($groupDN, 'group');

      // if group info found
      if ($groupInfo)
      {
        // get subgroups of group if any
        if ($subGroups = $groupInfo->getAttribute('memberOf')) {
          foreach ($subGroups as $group) {
            $results[] = $group;
          }
        }
      }
    }

    return $results;
  }

  // extract CN part of a DN
  private function extractCNs($dns)
  {
    $results = [];

    foreach ($dns as $dn)
    {
      $start = strpos($dn, '=') + 1;
      $endlength = strpos($dn, ',') - $start;

      $results[] = substr($dn, $start, $endlength);
    }

    return $results;
  }
}
