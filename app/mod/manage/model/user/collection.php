<?php
class manage_model_user_collection extends core_collection{

    public function prepareSql(){
        $sql = "SELECT {$this->getTable()}.*, parent_user.fullname as parent_fullname, user_role.role_name FROM {$this->getTable()} as user
                LEFT JOIN user_role ON {$this->getTable()}.role_id=user_role.role_id
                LEFT JOIN {$this->getTable()} as parent_user ON {$this->getTable()}.parent_id=parent_user.user_id
                WHERE 1 ";
        $this->setSql($sql);
        return $this;
    }


    public function addRoleFilter( $role_id ){
        $sql = $this->getSql();
        $sql .= " AND ({$this->getTable()}.role_id=:role_id)";
        $this->setSql($sql);
        $this->setSqlValue('role_id',$role_id);
        //core_debug::dump($this);
       // echo $sql;
        return $this;
    }

    public function addParentUserFilter( $parent_id ){
        $sql = $this->getSql();
        $sql .= " AND ({$this->getTable()}.parent_id=:parent_id)";
        $this->setSql($sql);
        $this->setSqlValue('parent_id',$parent_id);
        return $this;
    }

}