<?php

namespace Core;

class Request
{
    public static $attributes = [];

    public static function attr($attrs) {
        self::$attributes = array_merge(self::$attributes, $attrs);
    }

    public static function get($attr) {
        if (!array_key_exists($attr, self::$attributes)) {
            throw new \Exception("Field '$attr' is missing from request attributes.", 400);
        }

        return self::$attributes[$attr];
    }

}