<?php
class core_layout{

    /** @var  core_block_page */
    protected $_page;


    /**
     * @param core_block $block | null
     */
    public function __construct( $block = null ){
        if( $block and $block instanceof core_block){
            $this->_page = $block;
        }else{
            $this->initDefault();
        }
    }

    /**
     *
     */
    public function initDefault(){
        $this->_page = new core_block_page();
    }

    /**
     * @param core_block $block
     * @return $this
     */
    public function setPageBlock( $block ){
        $this->_page = $block;
        return $this;
    }

    /**
     * @return core_block_page
     */
    public function getPageBlock(){
        return $this->_page;
    }

    public function render(){
        echo $this->_page->renderHtml();
    }

    /**
     * @param $block_name
     * @param $block
     * @return $this
     */
    public function addBlock( $block_name, $block ){
        $this->_page->addChild( $block_name, $block );
        return $this;
    }

}