<?php

namespace App\Service\Generator;

use LightSaml\Model\Protocol\AuthnRequest;
use LightSaml\Model\Context\SerializationContext;
use LightSaml\Model\Assertion\Issuer;
use LightSaml\SamlConstants;
use DateTime;

class SAML2Generator
{
  public static function getRequestURL(
    $id = '',
    $loginUrl = 'https://example.com/saml2',
    $issuer = 'urn:example.com',
    $consumerUrl = 'https://example.com'
  )
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

  public static function generateID()
  {
    return 'id_' . bin2hex(openssl_random_pseudo_bytes(24));
  }
}
