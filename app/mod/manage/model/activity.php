<?php
class manage_model_activity extends core_model{

    public function __construct(){
        parent::__construct('activity','activity_id');
    }

    public function _beforeSave(){
        parent::_beforeSave();
        $this->setData('name',strtolower($this->getData('name')));

    }

}