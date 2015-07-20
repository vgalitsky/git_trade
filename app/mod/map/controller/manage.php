<?php

class map_controller_manage extends core_controller
{


    public function indexAction()
    {
        $index = new map_block_manage();
        $index->renderHtml();
    }
}