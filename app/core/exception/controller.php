<?php
class core_exception_controller extends core_exception{

    public function __construct( $msg ){
	core_log::log($msg,'controller.exception.log');
    }

}