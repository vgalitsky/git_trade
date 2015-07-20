<?php
class manage_model_event extends core_model{
    public function __construct(){
        parent::__construct('event','event_id');
    }

    public function _afterLoad(){
        $this->setData('title',$this->getData('user_fullname').' ('.$this->getData('city_name').') '.date('d.m.Y H:i', $this->getData('date')));
        $this->setData('img','<img src="'.app::getUrl($this->getData('image')).'" width="350px"/>');
        /** @var map_block_map_marker_content $content_block */
        $content_block = new map_block_map_marker_content();
        $content_block->setVar('event',$this);
        $this->setData('content', $content_block->getHtml() );
        return $this;
    }
}