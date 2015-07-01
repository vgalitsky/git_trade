<?php
class core_block_head extends core_block{
    public function __construct($template = null){
        $this->setTemplate('page/head.phtml');
    }
}