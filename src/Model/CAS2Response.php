<?php

namespace App\Model;

use Exception;

class CAS2Response
{
  private $user;
  private $attributes;
  private $longTerm;

  public function __construct($user = '', $attributes = [], $longTerm = false)
  {
    $this->user = $user;
    $this->attributes = $attributes;
    $this->longTerm = $longTerm;
  }

  public function getXML()
  {
    $responseXML = '<cas:serviceResponse xmlns:cas="http://www.yale.edu/tp/cas">';
    $responseXML .= '<cas:authenticationSuccess>';
    $responseXML .= $this->getUserXML();
    $responseXML .= '<cas:attributes>';
    $responseXML .= '<cas:authenticationDate>' . gmdate('Y-m-d\TH:i:s\Z', (new \DateTime)->format('U')) . '</cas:authenticationDate>';
    $responseXML .= '<cas:longTermAuthenticationRequestTokenUsed>false</cas:longTermAuthenticationRequestTokenUsed>';
    $responseXML .= '<cas:isFromNewLogin>true</cas:isFromNewLogin>';
    $responseXML .= $this->getCustomAttributesXML();
    $responseXML .= '</cas:attributes>';
    $responseXML .= '</cas:authenticationSuccess>';
    $responseXML .= '</cas:serviceResponse>';

    return $responseXML;
  }

  private function getUserXML()
  {
    return '<cas:user>' . $this->user . '</cas:user>';
  }

  private function getCustomAttributesXML()
  {
    $attributesXML = '';

    foreach ($this->attributes as $attr)
      $attributesXML .= '<cas:' . $attr->name . '>' . $attr->value . '</cas:' . $attr->name . '>';

    return $attributesXML;
  }
}
