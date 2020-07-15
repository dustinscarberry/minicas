<?php

namespace App\Service\Generator;

use LightSaml\Model\Protocol\AuthnRequest;
use LightSaml\Model\Context\SerializationContext;
use LightSaml\Model\Assertion\Issuer;
use LightSaml\SamlConstants;
use DateTime;

/**
 * Generate information related to SAML2
 *
 * @package DAS
 * @author Dustin Scarberry <dustin@codeclouds.net>
 */
class SAML2Generator
{
  /**
   * Return SAML2 login request URL
   *
   * @param string $id
   * @param string $loginUrl
   * @param string $issuer
   * @param string $consumerUrl
   * @return string
   */
  public static function getRequestURL(
    string $id = '',
    string $loginUrl = 'https://example.com/saml2',
    string $issuer = 'urn:example.com',
    string $consumerUrl = 'https://example.com'
  ): string
  {
    $request = new AuthnRequest();
    $request
      ->setAssertionConsumerServiceURL($consumerUrl)
      ->setProtocolBinding(SamlConstants::BINDING_SAML2_HTTP_POST)
      ->setID($id)
      ->setIssueInstant(new DateTime())
      ->setDestination($loginUrl)
      ->setIssuer(new Issuer($issuer)
    );

    $serializationContext = new SerializationContext();
    $request->serialize($serializationContext->getDocument(), $serializationContext);
    $request = $serializationContext->getDocument()->saveXML();
    $samlEncoded = urlencode(base64_encode(gzdeflate($request)));

    return $loginUrl . '?SAMLRequest=' . $samlEncoded;
  }

  /**
   * Return SAML2 ID token
   *
   * @return string
   */
  public static function generateID(): string
  {
    return 'id_' . bin2hex(openssl_random_pseudo_bytes(24));
  }
}
