<?php

namespace App\Service\Generator;

use Symfony\Component\HttpFoundation\Response;
use App\Model\CASError;
use App\Exception\InvalidTicketException;
use App\Exception\InvalidServiceException;
use App\Exception\InvalidRequestException;
use Exception;

/**
 * Generate information for CAS
 *
 * @package MiniCAS
 * @author Dustin Scarberry <bitnsbytes1001@gmail.com>
 */
class CASGenerator
{
  /**
   * Return new CAS Ticket token
   *
   * @return string
   */
  public static function generateTicket(): string
  {
    return 'ST-' . bin2hex(openssl_random_pseudo_bytes(12));
  }

  /**
   * Return CAS Ticket reply URL
   *
   * @param string $replyToUrl
   * @param string $ticket
   * @return string
   */
  public static function getTicketRedirectUrl(string $replyToUrl, string $ticket): string
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

  /**
   * Return CAS error xml response
   *
   * @param Exception $e
   * @return string
   */
  public static function getErrorResponse(Exception $e): string
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
