<?php

namespace App\Service\Generator;

use Hashids\Hashids;

class HashIdGenerator
{
  public static function generate()
  {
    $hashids = new Hashids($_ENV['APP_SECRET'], 12);
    return $hashids->encode(time() . mt_rand());
  }
}
