<?php
class manage_controller_index extends manage_controller_manage{



    public function indexAction(){
        $main = $this->_prepareContent();
        $this->getLayout()->getPageBlock()->addChild('root', $main);

        $this->renderLayout();
    }

    public function ajaxindexAction(){
        $main = new manage_block_main();
        $main->renderHtml();
    }
}