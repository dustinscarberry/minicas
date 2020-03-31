<?php

namespace App\Service\Generator;

class UtilityGenerator
{
  public static function cleanService($service = '')
  {
    $service = strtok($service, '?');
    $service = rtrim($service, '/');
    $service = str_replace('https://', '', $service);
    $service = str_replace('http://', '', $service);
    $service = strtolower($service);

    return $service;
  }
}
