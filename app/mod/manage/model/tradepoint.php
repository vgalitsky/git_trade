<?php
class manage_model_tradepoint extends core_model{

    public function __construct(){
        parent::__construct('trade_point','trade_point_id');
    }

    public function _afterLoad(){
        $this->setData('title',$this->getData('name'));
        $this->setData('img','<img src="'.app::getUrl($this->getData('image')).'" width="350px"/>');
        /** @var map_block_map_marker_content $content_block */
        $content_block = new map_block_map_marker_tradepoint_content();
        $content_block->setVar('tradepoint',$this);
        $this->setData('content', $content_block->getHtml() );

        return $this;
    }

}