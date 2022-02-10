<?php

namespace Core;

class View
{
  public static function render($view, $args = [])
  {
    extract($args, EXTR_SKIP);

    $__file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

    if (!is_readable($__file)) {
      throw new \Exception("$__file not found");
    }

    require $__file;
  }
}
