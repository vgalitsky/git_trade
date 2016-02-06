<?php
class manage_controller_competitor extends manage_controller_manage{

    public function indexAction(){
        $main = $this->_prepareContent();
        $main->addChild('content', new manage_block_competitor_list());
        $this->getLayout()->getPageBlock()->addChild('root', $main);
        $this->renderLayout();
    }

    public function listAction(){
     $list = new manage_block_competitor_list();
        $list->renderHtml();
    }

    public function add_formAction(){
        $competitor_id = app::getRequest()->getParam('competitor_id', null);
        $competitor = app::getModel('manage_model_competitor')->load($competitor_id);
        $block = new manage_block_competitor_addform();
        $block->setVar('competitor',$competitor);
        $block->renderHtml();
    }

    public function saveAction(){
        $response = new manage_block_template('response.phtml');
        $competitor_data = app::getRequest()->getParam('competitor', array());
        $competitor = app::getModel('manage_model_competitor')->load($competitor_data['competitor_id']);
        try{
            $competitor->setData($competitor_data)
                ->save();
            $response->setVar('success','Успешно');
        }catch(Exception $e){
            $response->setVar('error',$e->getMessage());
        }
        if(app::getRequest()->getParam('ajax',1)!=1){
            $this->redirect('manage/competitor/index');
        }
        $response->renderHtml();
    }

    public function deleteAction(){
        $response = new manage_block_template('response.phtml');
        $competitor_id = app::getRequest()->getParam('competitor_id', null);
        if($competitor_id){
            try{
                $competitor = app::getModel('manage_model_competitor')->load($competitor_id);
                $competitor->setData('deleted',1);
                $competitor->save();
                $response->setVar('success','Удалено');
            }catch(Exception $e){
                $response->setVar('error',$e->getMessage());
            }
        }
        $response->renderHtml();
    }

    public function gridAction(){
        $block = new manage_block_competitor_grid();
        $collection = app::getModel('manage_model_competitor')->getCollection();
        $block->setVar('collection', $collection);
        $block->renderHtml();
    }
}