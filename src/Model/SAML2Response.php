<?php

namespace App\Model;

use LightSaml\Model\Context\DeserializationContext;
use LightSaml\Credential\X509Certificate;
use LightSaml\Model\Protocol\Response;
use LightSaml\Credential\KeyHelper;
use Exception;

class SAML2Response
{
  private $response;
  private $assertion;

  public function loadFromString($responseString)
  {
    //parse response to object
    $deserializationContext = new DeserializationContext();
    $deserializationContext->getDocument()->loadXML($responseString);
    $this->response = new Response();
    $this->response->deserialize($deserializationContext->getDocument()->firstChild, $deserializationContext);

    //get assertion
    $this->assertion = $this->response->getFirstAssertion();
  }

  public function validate(?string $signingCert)
  {
    $this->validateSignature();
    $this->validateConditions();

    if ($signingCert)
      $this->validateKnownPublicSigningCert($signingCert);
  }

  public function getSubject()
  {
    return $this->assertion
      ->getSubject()
      ->getNameID()
      ->getValue();
  }

  public function getSubjectFormat()
  {
    return $this->assertion
      ->getSubject()
      ->getNameID()
      ->getFormat();
  }

  public function getSessionId()
  {
    return $this->response->getInResponseTo();
  }

  private function validateSignature()
  {
    $signingCerts = $this->assertion->getSignature()->getAllCertificates();
    $validSignature = false;

    foreach ($signingCerts as $signingCert)
    {
      //create public key to verify signature
      $publicCert = new X509Certificate();
      $publicCert->setData($signingCert);
      $key = KeyHelper::createPublicKey($publicCert);

      //get signature from assertion
      $signatureReader = $this->assertion->getSignature();

      //validate signature
      if ($signatureReader->validate($key))
      {
        $validSignature = true;
        break;
      }
    }

    if (!$validSignature)
      throw new Exception('SAML Response Error: Signature Invalid');
  }

  private function validateConditions()
  {
    //check time conditions
    $conditions = $this->assertion->getConditions();
    $notBefore = $conditions->getNotBeforeTimestamp();
    $notOnOrAfter = $conditions->getNotOnOrAfterTimestamp();

    if (time() < $notBefore || time() >= $notOnOrAfter)
      throw new Exception('SAML Response Error: Timestamp invalid');
  }

  private function validateKnownPublicSigningCert($signingCert)
  {
    $signingCerts = $this->assertion->getSignature()->getAllCertificates();
    $validHost = false;

    foreach ($signingCerts as $cert)
    {
      if ($signingCert == $cert)
      {
        $validHost = true;
        break;
      }
    }

    if (!$validHost)
      throw new Exception('SAML Response Error: Signature not trusted');
  }
}
