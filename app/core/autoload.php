<?php
class core_autoload {

    static $_BASE_CODE_PATH = 'app';
    static $_CORE_PATH = 'core';
    static $_MOD_PATH = 'mod';

    public function __construct(){
    }

    /**
     * @param $class_name
     */
    static function autoload( $class_name ){
        if( ! self::core_autoload( $class_name ) ){
            self::mod_autoload( $class_name );
        }
    }

    /**
     * @param $class_name
     * @return bool
     */
    static function core_autoload( $class_name ){
        $path = preg_replace('/'.CS.'/','/', $class_name);
        $path = self::$_BASE_CODE_PATH . DS . $path . '.php';
        if(file_exists($path)){
            include_once($path);
            return true;
        }
        return false;
    }

    /**
     * @param $class_name
     * @param string $type
     * @return bool
     */
    static function mod_autoload( $class_name, $type = '' ){
        $path = preg_replace('/'.CS.'/','/', $class_name);
        $path = self::$_BASE_CODE_PATH . DS. self::$_MOD_PATH .DS. ($type ? ($type .DS) : '') . $path . '.php';
        if(file_exists($path)){
            include_once($path);
            return true;
        }
        return false;
    }
}

spl_autoload_register('core_autoload::autoload');