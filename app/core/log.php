<?php
class core_log{

    const LOG_DIR = 'log';

    static function log( $message, $logfile = 'core.log' ){
        if( !is_string($message)  ){
            $message = core_debug::dump($message, false);
        }
        $fpath = app::getConfig('dir/sd'.DS.self::LOG_DIR.DS.$logfile);
        core_fs::createDirIfNotExists(dirname($fpath));
        file_put_contents($fpath,$message);
        return $fpath;
    }

}
