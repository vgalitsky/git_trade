<?php
class service_helper_sql extends core_helper{

    static function assertSqlReadonly( $sql ){
        if(preg_match('/(delete)|(update)|(insert)|(truncate)|(drop)/sumix', $sql)){
            throw new Exception('Service does not accept modifications');
        }
    }
}