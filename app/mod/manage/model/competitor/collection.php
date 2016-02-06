<?php
class manage_model_competitor_collection extends core_collection{

    public function competitorReferenceForMobile(){
        $sql = "SELECT competitor_id , name FROM {$this->getTable()} WHERE deleted IS NULL";
        $this->setSql($sql);
        return $this;
    }

    public function prepareSql(){
        parent::prepareSql();
        $sql = $this->getSql();
        $sql .=' WHERE deleted IS NULL';
        $this->setSql($sql);
        return $this;
    }
}