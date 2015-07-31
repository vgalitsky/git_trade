<?php
class core_debug {

    static function dump($var, $output=true){
        ob_start();
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        $out = ob_get_contents();
        ob_end_clean();
        if($output){
            echo $out;
        }
        return $out;

    }
}