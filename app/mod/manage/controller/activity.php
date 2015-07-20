<?php
class manage_controller_activity extends manage_controller_manage{

    public function indexAction(){
        $main = $this->_prepareContent();
        $main->addChild('content', new manage_block_activity_list());
        $this->getLayout()->getPageBlock()->addChild('root', $main);
        $this->renderLayout();
    }

    public function listAction(){
     $list = new manage_block_activity_list();
        $list->renderHtml();
    }

    public function add_formAction(){
        $activity_id = app::getRequest()->getParam('activity_id', null);
        $activity = app::getModel('manage_model_activity')->load($activity_id);
        $block = new manage_block_activity_addform();
        $block->setVar('activity',$activity);
        $block->renderHtml();
    }

    public function saveAction(){
        $response = new manage_block_template('response.phtml');
        $activity_data = app::getRequest()->getParam('activity', array());
        $activity = app::getModel('manage_model_activity')->load($activity_data['activity_id']);
        try{
            $activity->setData($activity_data)
                ->save();
            $response->setVar('success','Успешно');
        }catch(Exception $e){
            $response->setVar('error',$e->getMessage());
        }
        if(app::getRequest()->getParam('ajax',1)!=1){
            $this->redirect('manage/activity/index');
        }
        $response->renderHtml();
    }

    public function deleteAction(){
        $response = new manage_block_template('response.phtml');
        $activity_id = app::getRequest()->getParam('activity_id', null);
        if($activity_id){
            try{
                $activity = app::getModel('manage_model_activity')->load($activity_id);
                $activity->delete();
                $response->setVar('success','Удалено');
            }catch(Exception $e){
                $response->setVar('error',$e->getMessage());
            }
        }
        $response->renderHtml();
    }

    public function gridAction(){
        $block = new manage_block_activity_grid();
        $collection = app::getModel('manage_model_activity')->getCollection();
        $block->setVar('collection', $collection);
        $block->renderHtml();
    }
}