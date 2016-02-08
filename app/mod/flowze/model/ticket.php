<?php
class flowze_model_ticket extends core_model{

    public function __construct(){
        parent::__construct('flowze_ticket','ticket_id');
    }

    protected function _validateData(){
        if(!$this->getData('subject')){
            throw new core_exception('subject is empty');
        }
        return $this;
    }
}