<?php

namespace System\Session;

class Session{
    public static function set($name, $value){
        $_SESSION[$name] = $value;
    }

    public static function get($name){
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }

    public static function remove($name){
        if(isset($_SESSION[$name]))
        unset($_SESSION[$name]);
    }
}
