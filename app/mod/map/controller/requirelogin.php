<?php
class map_controller_requirelogin extends core_controller{

    protected function _predispatchAction(){
        if($this->_actionMethod=='logoutAction'){
            return;
        }
        /** @var core_session $session */
        $session = $this->getRequest()->getSession();

        $user = $session->getLoggedInUser();
        if(!$user){
            $logged_in = false;
            $loginData = $this->getRequest()->getParam('login',null);
            if(is_array($loginData)){
                $user = new core_model_user();
                $logged_in = $user->authenticate( $loginData['username'], $loginData['password'] );
            }
            if(!$logged_in){

                $this->_actionMethod = 'requireLoginAction';
            }
        }
    }

    public function requireLoginAction(){
        $this->initLayout();
        /** @var core_block_page $page */
        $login = new core_block('page/login/form.phtml');
        $loginData = $this->getRequest()->getParam('login',null);
        if(is_array($loginData)){
            $login->setVar('error','Неверное имя пользователя или пароль');
        }
        $this->getLayout()->getPageBlock()->addChild('head', new core_block_head());
        $this->getLayout()->getPageBlock()->addChild('root', $login);
        $this->renderLayout();
    }

    public function logoutAction(){
        app::getSession()->destroy();
        header('Location: '. app::getUrl('map/index/activity'));
    }

}