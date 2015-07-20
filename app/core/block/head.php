<?php
class core_block_head extends core_block{

    protected $_js;
    protected $_css;



    protected function _init(  ){
        parent::_init();
        $this->setTemplate('page/head.phtml');
    }

    public function addJs( $js, $mod = null ){
        $this->_js[] = array('item'=>$js, 'mod'=>$mod);
    }

    public function addCss( $css, $mod=null){
        $this->_css[]= array('item'=>$css, 'mod'=>$mod);
    }

    public function getJs(){
        return $this->_js;
    }

    public function getCss(){
        return $this->_css;
    }

}