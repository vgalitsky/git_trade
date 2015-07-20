<?php
class core_debug {

    static function dump($var){
        echo '<pre>';
        var_dump($var);
        echo '</pre>';

    }
}