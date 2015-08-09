<?php
class manage_model_event_collection extends core_collection{

    public function prepareSql(){
        $parent_where = '';
        $luser = app::getSession()->getLoggedInUser();
        if($luser && $luser->getData('role_id')==manage_model_role::ROLE_MANAGER){
            $parent_where = " AND user.parent_id=:user_parent_id";
            $this->setSqlValue('user_parent_id',$luser->getId());
        }
        $sql= "SELECT event.*,
                user.user_id, user.fullname as user_fullname, user.marker_color as marker_color,
                city.city_id as city_id, city.name as city_name,
                activity.activity_id as activity_id, activity.name as activity_name
              FROM `{$this->getTable()}` as event
              RIGHT JOIN user ON event.user_id=user.user_id {$parent_where}
              LEFT JOIN city ON event.city_id=city.city_id
              LEFT JOIN activity ON event.activity_id=activity.activity_id
              WHERE 1 ";
        $this->setSql($sql);
        return $this;
    }

    public function prepareFilter($filter){
        if(!is_array($filter)){
            return $this;
        }
        if(is_array($filter['date'])){
            $this->addDateFilter( $filter['date'] );
        }

    }

    public function addDateFilter( $date ){
        $date_from = strtotime($date['from']);
        $date_to = strtotime($date['to']);
        $this->setSqlValue('date_from',$date_from);
        $this->setSqlValue('date_to',$date_to);

        $sql = $this->getSql();
        $sql.=" AND (event.date >= :date_from AND event.date <= :date_to) ";
        $this->setSql($sql);
        return $this;
    }
    protected function _beforeLoad(){
        parent::_beforeLoad();
        //die($this->getSql());
    }

}