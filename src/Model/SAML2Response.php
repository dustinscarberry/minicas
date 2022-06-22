<?php

namespace App\Model;

use LightSaml\Model\Context\DeserializationContext;
use LightSaml\Credential\X509Certificate;
use LightSaml\Model\Protocol\Response;
use LightSaml\Credential\KeyHelper;
use App\Service\Formatter\SamlFormatter;
use Exception;

class SAML2Response
{
  private $response;
  private $assertion;

  public function loadFromString($responseString)
  {
    //base64_decode raw response
    $responseString = base64_decode($responseString);

    //parse response to object
    $deserializationContext = new DeserializationContext();
    $deserializationContext->getDocument()->loadXML($responseString);
    $this->response = new Response();
    $this->response->deserialize($deserializationContext->getDocument()->firstChild, $deserializationContext);

    //get assertion
    $this->assertion = $this->response->getFirstAssertion();
  }

  public function validate(?string $signingCert = null, $ignoreSigningCertExpiration = false)
  {
    $this->validateSignature();
    $this->validateConditions();

    if ($signingCert)
      $this->validateKnownPublicSigningCert($signingCert, $ignoreSigningCertExpiration);
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

    foreach ($signingCerts as $signingCert) {
      //create public key to verify signature
      $publicCert = new X509Certificate();
      $publicCert->setData($signingCert);
      $key = KeyHelper::createPublicKey($publicCert);

      //get signature from assertion
      $signatureReader = $this->assertion->getSignature();

      //validate signature
      if ($signatureReader->validate($key)) {
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

  private function validateKnownPublicSigningCert($signingCert, $ignoreSigningCertExpiration)
  {
    $signingCerts = $this->assertion->getSignature()->getAllCertificates();
    $validHost = false;

    foreach ($signingCerts as $cert) {
      // get certificate details
      $certificateDetails = openssl_x509_parse(SamlFormatter::formatCertificateData($cert));

      if (
        (
          $signingCert == $cert
          && $certificateDetails['validTo_time_t'] >= time()
          && $certificateDetails['validFrom_time_t'] <= time()
        ) || (
          $signingCert == $cert
          && $ignoreSigningCertExpiration == true
        )
      ) {
        $validHost = true;
        break;
      }
    }

    if (!$validHost)
      throw new Exception('SAML Response Error: No valid signing certificate');
  }
}
