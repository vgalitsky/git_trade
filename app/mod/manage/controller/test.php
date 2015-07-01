<?php
class manage_controller_test extends core_controller{

    public function some_actionAction(){
        $testModel = app::getModel( 'manage_model_test' );
        $col = $testModel->getCollection();
        foreach( $col as $m){
            var_dump($m);
        }
        //var_dump($col);
        //$testModel->load(1);
        //$testModel->setData('field1',777);
        //$testModel->setData('field2',555);
       // $testModel->save();
        //var_dump($testModel);
        die('bla');
        $this->initLayout();
        $page = $this->getLayout()->getPageBlock();
        $page->addChild('content', new manage_block_test());
        $this->renderLayout();
    }

    public function ajaxAction(){
        $block = new manage_block_test();
        $block->renderHtml();
    }
}