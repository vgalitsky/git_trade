<?php
class core_fs{

    static function createDirIfNotExists($dir, $mode=0777, $recursive = true){
        if(!is_dir($dir)){
            mkdir($dir, $mode, $recursive);
        }
    }
}