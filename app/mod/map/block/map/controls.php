<?php

class map_block_map_controls extends core_block
{

    public function __construct()
    {
        parent::__construct('map/controls.phtml');

    }

    protected function _initVars()
    {

        parent::_initVars();
        $activity = new manage_model_activity();
        $this->setVar('activities', $activity->getCollection());

        $city = new manage_model_city();
        $this->setVar('cities', $city->getCollection());

        $user = new manage_model_user();
        $traders_collection = $user->getCollection();
        $traders_collection->addRoleFilter(manage_model_role::ROLE_AGENT);

//        $luser = app::getSession()->getLoggedInUser();
//        if($luser->getData('role_id') != manage_model_role::ROLE_ADMIN){
//            $traders_collection->addParentUserFilter( $luser->getId() );
//        }

        $traders_collection->addRoleFilter(manage_model_role::ROLE_AGENT);

        $this->setVar('traders', $traders_collection);

        $dateFrom = date('U')-60*60*24;
        $dateTo = (int)date('U');
        $this->setFilterVar('date',array('from'=>$dateFrom,'to'=>$dateTo));
    }
    public function setFilterVar($filter_var, $value){
        $filter = $this->getVar('filter');
        $filter = is_array($filter)?$filter:array();
        $filter[$filter_var] = $value;
        $this->setVar('filter',$filter);
        return $this;
    }
}