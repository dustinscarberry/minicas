<?php

namespace App\Service\Formatter;

/**
 * Formatter class for SAML certificates
 *
 * @package MiniCAS
 * @author Dustin Scarberry <bitnsbytes1001@gmail.com>
 */
class SamlFormatter
{
  public static function formatCertificateData($certificate)
  {
    $certificateData = self::extractCertificateData($certificate);
    return "-----BEGIN CERTIFICATE-----\r\n" . chunk_split($certificateData, 64) . "-----END CERTIFICATE-----";
  }

  public static function extractCertificateData($certificate)
  {
    // remove cert base64 headers
    $cert = str_replace("-----BEGIN CERTIFICATE-----", '', $certificate);
    $cert = str_replace("-----END CERTIFICATE-----", '', $cert);
    $cert = str_replace("\r\n", '', $cert);
    return str_replace("\r", '', $cert);
  }
}
