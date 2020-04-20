<?php

namespace App\Model;

use Exception;

class CAS1Response
{
  private $user;
  private $longTerm;

  public function __construct($user = '', $longTerm = false)
  {
    $this->user = $user;
    $this->longTerm = $longTerm;
  }

  public function getXML()
  {
    $responseXML = '<cas:serviceResponse xmlns:cas="http://www.yale.edu/tp/cas">';
    $responseXML .= '<cas:authenticationSuccess>';
    $responseXML .= $this->getUserXML();
    $responseXML .= '</cas:authenticationSuccess>';
    $responseXML .= '</cas:serviceResponse>';

    return $responseXML;
  }

  private function getUserXML()
  {
    return '<cas:user>' . $this->sanitizeXMLTag($this->user) . '</cas:user>';
  }

  private function sanitizeXMLTag($value)
  {
    $value = str_replace('<', '&lt;', $value);
    $value = str_replace('>', '&gt;', $value);
    $value = str_replace('&', '&amp;', $value);
    return $value;
  }

  private function sanitizeXMLAttribute($value)
  {
    $value = str_replace('<', '&lt;', $value);
    $value = str_replace('>', '&gt;', $value);
    $value = str_replace('&', '&amp;', $value);
    $value = str_replace('"', '&quot;', $value);
    $value = str_replace("'", '&apos;', $value);
    return $value;
  }
}
