<?php
class flowze_controller_abstract extends core_controller{

    protected function _predispatchAction(){
        return $this;
    }

}