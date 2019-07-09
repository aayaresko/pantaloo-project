<?php

class DataLayerHelper {

    public static $data = [];

    public static function set($key, $val){
        self::$data[$key] = $val;
    }

    public static function get($key){
        return isset(self::$data[$key]) ? self::$data[$key] : null;
    }

    public static function toJson($options =  JSON_PRETTY_PRINT & JSON_FORCE_OBJECT){
        return json_encode(self::$data, $options);
    }
}
