<?php

class Param {

    private static $param;

    public static function get($key) {
        $param = self::getArray();
        if (isset($param[$key])) {
            return $param[$key];
        } else {
            return "";
        }
    }

    public static function getArray() {
        if (self::$param != null) {
            return self::$param;
        } else {
            self::$param = json_decode(file_get_contents("php://input"), true);
            return self::$param;
        }
    }

}