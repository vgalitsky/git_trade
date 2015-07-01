<?php
class core_pdo{

    static function getAdapter($adapter, $config){
        $adapter = "core_pdo_{$adapter}_pdo";
        return new $adapter( $config );
    }
}