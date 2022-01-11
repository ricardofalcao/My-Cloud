<?php

namespace Core;

class  Input {
    
    static function check($arr, $on = false) {
        if ($on === false) {
            $on = $_REQUEST;
        }
        foreach ($arr as $value) {
            if (empty($on[$value])) {
                throw new \Exception("Field '$value' is missing", 900);
            }
        }
    }

    static function int($val) {
        $val = filter_var($val, FILTER_VALIDATE_INT);
        if ($val === false) {
            throw new \Exception('Invalid Integer', 901);
        }
        return $val;
    }

    static function str($val) {
        if (!is_string($val)) {
            throw new \Exception('Invalid String', 902);
        }

        $val = trim(htmlspecialchars($val));
        return $val;
    }

    static function bool($val) {
        $val = filter_var($val, FILTER_VALIDATE_BOOLEAN);
        return $val;
    }

    static function email($val) {
        $val = filter_var($val, FILTER_VALIDATE_EMAIL);
        if ($val === false) {
            throw new \Exception('Invalid Email', 903);
        }
        return $val;
    }

    static function url($val) {
        $val = filter_var($val, FILTER_VALIDATE_URL);
        if ($val === false) {
            throw new \Exception('Invalid URL', 904);
        }
        return $val;
    }
}