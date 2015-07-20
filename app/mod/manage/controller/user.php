<?php
class manage_controller_user extends core_controller{

    public function userAction(){
        $model = new manage_model_user();
        $model->authenticate('mtr','smxksmxk');
    }


    public function indexAction(){
        $main = $this->_prepareContent();
        $main->addChild('content', new manage_block_user_list());
        $this->getLayout()->getPageBlock()->addChild('root', $main);
        $this->renderLayout();
    }

    public function listAction(){
        $list = new manage_block_user_list();
        $list->renderHtml();
    }

    public function add_formAction(){
        $user_id = app::getRequest()->getParam('user_id', null);
        $user = app::getModel('manage_model_user')->load($user_id);
        $block = new manage_block_user_addform();
        $block->setVar('user',$user);
        $block->renderHtml();
    }

    public function saveAction(){
        $response = new manage_block_template('response.phtml');
        $user_data = app::getRequest()->getParam('user', array());
        $user = app::getModel('manage_model_user')->load($user_data['user_id']);
        try{

            $user->setData($user_data)
                ->save();
            $response->setVar('success','Успешно');
        }catch(Exception $e){
            $response->setVar('error',$e->getMessage());
        }
        if(app::getRequest()->getParam('ajax',1)!=1){
            $this->redirect('manage/user/index');
        }
        $response->renderHtml();
    }

    public function deleteAction(){
        $response = new manage_block_template('response.phtml');
        $user_id = app::getRequest()->getParam('user_id', null);
        if($user_id){
            try{
                $user = app::getModel('manage_model_user')->load($user_id);
                $user->delete();
                $response->setVar('success','Удалено');
            }catch(Exception $e){
                $response->setVar('error',$e->getMessage());
            }
        }
        $response->renderHtml();
    }

    public function gridAction(){
        $block = new manage_block_user_grid();
        /** @var manage_model_user_collection $collection */
        $collection = app::getModel('manage_model_user')->getCollection();
        $block->setVar('collection', $collection);
        $block->renderHtml();
    }
    
}