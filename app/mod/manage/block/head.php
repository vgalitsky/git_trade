<?php
class manage_block_head extends core_block_head{

    public function __construct(){
        parent::__construct();
        $this->addJs('js/jquery.js');
        $this->addJs('js/jquery/arrive.min.js');
        $this->addJs('js/jquery-ui/jquery-ui.min.js');
        $this->addCss('css/api.css');
        $this->addCss('css/jquery-ui/jquery-ui.css');
        //$this->addCss('css/jquery-ui/jquery-ui.min.css');
    }
}