<?php
class manage_model_activity_collection extends core_collection{

    public function activityReferenceForMobile(){
        $sql = "SELECT activity_id , name FROM {$this->getTable()}";
        $this->setSql($sql);
        return $this;
    }
}