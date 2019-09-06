<?php

namespace App\Model;

class CASError
{
  private $type;
  private $message;

  public function __construct($type = 'INVALID_TICKET', $message = '')
  {
    $this->type = $type;
    $this->message = $message;
  }

  public function getXML()
  {
    $responseXML = '<cas:serviceResponse xmlns:cas="http://www.yale.edu/tp/cas">';
    $responseXML .= '<cas:authenticationFailure code="' . $this->type . '">' . $this->message . '</cas:authenticationFailure>';
    $responseXML .= '</cas:serviceResponse>';

    return $responseXML;
  }
}
