<?php
class core_log{

    const LOG_DIR = 'log';

    static function log( $message, $logfile = 'core.log' ){
        if( !is_string($message)  ){
            $message = core_debug::dump($message, false);

        }
        $message = date('[m-d-Y H:i:s]:').$message;

        $fpath = app::getConfig('dir/sd').DS.self::LOG_DIR.DS.$logfile;
        core_fs::createDirIfNotExists(dirname($fpath));
        $fh = fopen($fpath,'a+');
        fputs($fh,$message."\n");
        fclose($fh);

        return $message;
    }

    static function logCoreException($e){
        return self::log( "{APP EXCEPTION}:".$e->getMessage()."\n".$e->getTraceAsString(), 'core.exception.log' );
    }

    static function logException($e){
        return self::log( "{EXCEPTION}:".$e->getMessage()."\n".$e->getTraceAsString(), 'exception.log' );
    }

}
