<?php
class core_str{

    static function applyVars( $str, $vars ){
        foreach($vars as $var => $val){
            $str = str_replace("%{$var}%", $val, $str);
        }
        return $str;
    }
}