<?php
class map_block_manage extends core_block{

    protected function _init( ){
        parent::_init();
        $this->setTemplate('manage.phtml');
    }
}