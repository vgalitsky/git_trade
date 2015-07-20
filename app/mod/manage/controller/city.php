<?php
class manage_controller_city extends manage_controller_manage{

    public function indexAction(){
        $main = $this->_prepareContent();
        $main->addChild('content', new manage_block_city_list());
        $this->getLayout()->getPageBlock()->addChild('root', $main);
        $this->renderLayout();
    }

    public function listAction(){
        $list = new manage_block_city_list();
        $list->renderHtml();
    }

    public function add_formAction(){
        $city_id = app::getRequest()->getParam('city_id', null);
        $city = app::getModel('manage_model_city')->load($city_id);
        $block = new manage_block_city_addform();
        $block->setVar('city',$city);
        $block->renderHtml();
    }

    public function saveAction(){
        $response = new manage_block_template('response.phtml');
        $city_data = app::getRequest()->getParam('city', array());
        $city = app::getModel('manage_model_city')->load($city_data['city_id']);
        try{
            $city->setData($city_data)
                ->save();
            $response->setVar('success','Успешно');
        }catch(Exception $e){
            $response->setVar('error',$e->getMessage());
        }
        if(app::getRequest()->getParam('ajax',1)!=1){
            $this->redirect('manage/city/index');
        }
        $response->renderHtml();
    }

    public function deleteAction(){
        $response = new manage_block_template('response.phtml');
        $city_id = app::getRequest()->getParam('city_id', null);
        if($city_id){
            try{
                $city = app::getModel('manage_model_city')->load($city_id);
                $city->delete();
                $response->setVar('success','Удалено');
            }catch(Exception $e){
                $response->setVar('error',$e->getMessage());
            }
        }
        $response->renderHtml();
    }

    public function gridAction(){
        $block = new manage_block_city_grid();
        $collection = app::getModel('manage_model_city')->getCollection();
        $block->setVar('collection', $collection);
        $block->renderHtml();
    }
}