<?php

namespace Core;

class View
{
  public static function render($view, $args = [])
  {
    extract($args, EXTR_SKIP);

    $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

    if (!is_readable($file)) {
      throw new \Exception("$file not found");
    }

    require $file;
  }
}
