<?php
class core_fs{

    static function createDirIfNotExists($dir, $mode=0777, $recursive = true){
        if(!is_dir($dir)){
            $oldmask = umask(0);
            @mkdir($dir, $mode, $recursive);
            umask($oldmask);
        }
    }
}