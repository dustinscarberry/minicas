<?php

namespace App\Service\Generator;

use Hashids\Hashids;

/**
 * Generate information relating to hashIds for objects
 *
 * @package DAS
 * @author Dustin Scarberry <dustin@codeclouds.net>
 */
class HashIdGenerator
{
  /**
   * Return a random hashId
   *
   * @return string
   */
  public static function generate(): string
  {
    $hashids = new Hashids($_ENV['APP_SECRET'], 12);
    return $hashids->encode(time() . mt_rand());
  }
}
