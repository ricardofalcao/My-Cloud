<?php

namespace Core;

class Asset
{
    public static function path($asset) {
        $feup = gethostname() === 'gnomo.fe.up.pt';
        if ($feup) {
            return '/~up201704220/sie/mycloud' . $asset;
        }

        return $asset;
    }

    public static function get($asset) {
        echo self::path($asset);
    }

}