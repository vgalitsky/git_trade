<?php
class manage_model_competitor extends core_model{

    public function __construct(){
        parent::__construct('competitor','competitor_id');
    }

    public function _beforeSave(){
        parent::_beforeSave();
        $this->setData('name',strtolower($this->getData('name')));

    }

}