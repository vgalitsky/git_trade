<?php
class manage_model_activity_collection extends core_collection{

    public function activityReferenceForMobile(){
        $sql = "SELECT activity_id , name FROM {$this->getTable()} WHERE deleted IS NULL";
        $this->setSql($sql);
        return $this;
    }

    public function prepareSql(){
        parent::prepareSql();
        $sql = $this->getSql();
        $sql.=' WHERE deleted IS NULL';
        $this->setSql($sql);
        return $this;
    }


}