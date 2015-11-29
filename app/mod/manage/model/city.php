<?php
class manage_model_city extends core_model{

    public function __construct(){
        parent::__construct('city','city_id');
    }

    public function _beforeSave(){
        parent::_beforeSave();
        $this->setData('name',strtolower($this->getData('name')));

    }

    public function getManagerCollection(){
        $manager = new manage_model_user();
        $manager_collection = $manager->getCollection();
        $manager_collection->addRoleFilter( manage_model_role::ROLE_MANAGER );
        $manager_collection->addCityFilter( $this->getId() );
        return $manager_collection;
    }

}