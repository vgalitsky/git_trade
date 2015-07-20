<?php
class manage_controller_manage extends core_controller{

    protected function _prepareContent(){
        $this->initLayout();
        /** @var core_block_page $page */
        $page = $this->getLayout()->getPageBlock();
        $page->addChild('head',new manage_block_head());
        $main = new manage_block_main();
        $main->addChild('left-menu', new manage_block_template('left-menu.phtml'));
        return $main;
    }
}
