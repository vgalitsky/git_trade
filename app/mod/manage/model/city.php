<?php
class manage_model_city extends core_model{

    public function __construct(){
        parent::__construct('city','city_id');
    }

    public function _beforeSave(){
        parent::_beforeSave();
        $this->setData('name',strtolower($this->getData('name')));

    }

}