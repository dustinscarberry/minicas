<?php

namespace App\Service\Generator;

use Symfony\Component\HttpFoundation\Response;
use App\Model\CASError;
use App\Exception\InvalidTicketException;
use App\Exception\InvalidServiceException;
use App\Exception\InvalidRequestException;
use Exception;

class CASGenerator
{
  public static function generateTicket()
  {
    return 'ST-' . bin2hex(openssl_random_pseudo_bytes(12));
  }

  public static function getTicketRedirectUrl(string $replyToUrl, string $ticket)
  {
    $urlParts = parse_url($replyToUrl);

    $url = $urlParts['scheme'] . '://';
    $url .= $urlParts['host'];
    $url .= (isset($urlParts['path']) ? $urlParts['path'] : '');
    $url .= '?';
    $url .= (isset($urlParts['query']) ? $urlParts['query'] . '&' : '');
    $url .= 'ticket=' . $ticket;

    return $url;
  }

  public static function getErrorResponse(Exception $e)
  {
    $type = get_class($e);

    if ($type == InvalidTicketException::class)
      $casResponse = new CASError(
        'INVALID_TICKET',
        $e->getMessage()
      );
    else if ($type == InvalidServiceException::class)
      $casResponse = new CASError(
        'INVALID_SERVICE',
        $e->getMessage()
      );
    else if ($type == InvalidRequestException::class)
      $casResponse = new CASError(
        'INVALID_REQUEST',
        $e->getMessage()
      );
    else
      $casResponse = new CASError(
        'INTERNAL_ERROR',
        'An internal error has occured'
      );

    return $casResponse->getXML();
  }
}
