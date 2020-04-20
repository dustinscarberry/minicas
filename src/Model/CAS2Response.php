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
    return '<cas:user>' . $this->sanitizeXMLTag($this->user) . '</cas:user>';
  }

  private function getCustomAttributesXML()
  {
    $attributesXML = '';

    foreach ($this->attributes as $attr)
    {
      if (is_array($attr->value))
      {
        foreach ($attr->value as $multiAttr) {
          $tagName = $this->sanitizeXMLTag($attr->name);
          $attrValue = $this->sanitizeXMLTag($multiAttr);
          $attributesXML .= '<cas:' . $tagName . '>' . $attrValue . '</cas:' . $tagName . '>';
        }
      }
      else {
        $tagName = $this->sanitizeXMLTag($attr->name);
        $attrValue = $this->sanitizeXMLTag($attr->value);
        $attributesXML .= '<cas:' . $tagName . '>' . $attrValue . '</cas:' . $tagName . '>';
      }
    }

    return $attributesXML;
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
