<?php
class manage_model_test extends core_model{

    public function __construct(){
        parent::__construct();
        $this->_init('test', 'test_id');
    }
}