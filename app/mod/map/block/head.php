<?php
class map_block_head extends core_block_head{


    protected function _init(){
        parent::_init();
       // $this->addJs('https://maps.googleapis.com/maps/api/js?v=3.exp');
        $this->addJs('js/jquery.js','manage');
        $this->addJs('js/jquery/arrive.min.js', 'manage');
        $this->addJs('js/jquery-ui/jquery-ui.min.js', 'manage');
        //$this->addJs('js/jquery-ui/spectrum.js', 'manage');
        $this->addJs('js/markerclusterer.js');
        //$this->addJs('js/infobubble.js');
        $this->addCss('css/jquery-ui/jquery-ui.css','manage');
        $this->addCss('css/api.css','manage');
        //$this->addCss('css/spectrum.css','manage');
        $this->addCss('css/map.css');
    }

}