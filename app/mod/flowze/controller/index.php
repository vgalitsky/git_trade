<?php
class flowze_controller_index extends flowze_controller_abstract{

    /** @var  core_block */
    protected $_wrapper;

    protected function _initWrapper(){
        $this->_wrapper = new flowze_block_flowze();
        $this->getLayout()->getPageBlock()->addChild('root', $this->_wrapper);
    }

    protected function _addChild( $name, core_block $child_block ){
        $this->_wrapper->addChild($name, $child_block);
        return $this;
    }

    public function indexAction(){
        $this->initLayout();
        $this->_initWrapper();
        $this->renderLayout();
    }


}