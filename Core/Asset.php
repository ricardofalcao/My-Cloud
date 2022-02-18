<?php

namespace Core;

class Asset
{
    public static function path($asset) {
        $feup = strpos(gethostname(), 'fe.up') !== false;
        if ($feup) {
            return '/~up201704220/sie/mycloud' . $asset;
        }

        return $asset;
    }

    public static function get($asset) {
        echo self::path($asset);
    }

}