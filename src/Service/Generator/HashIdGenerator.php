<?php

namespace App\Service\Generator;

use Hashids\Hashids;

/**
 * Generate information relating to hashIds for objects
 *
 * @package MiniCAS
 * @author Dustin Scarberry <bitnsbytes1001@gmail.com>
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
