<?php
class manage_model_role extends core_model{

    const ROLE_ADMIN   = 1;
    const ROLE_MANAGER = 2;
    const ROLE_AGENT   = 3;

    public function __construct(){
        parent::__construct('user_role','role_id');
    }
}