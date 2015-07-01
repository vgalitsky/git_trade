<?php
class core_block_header extends core_block{
    public function __construct($template = null){
        $this->setTemplate('page/header.phtml');
    }
}