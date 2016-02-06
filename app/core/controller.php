<?php
class core_controller {
    const DIR = 'controller';

    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = 'index';
    const ACTION_SUFFIX = 'Action';

    protected $_actionMethod;

    /** @var  core_request */
    protected $_request;

    /** @var  core_layout */
    protected $_layout;

    /**
     * @param $request
     * @return $this
     */
    public function setRequest( $request ){
        $this->_request = $request;
        return $this;
    }

    /**
     * @return core_request
     */
    public function getRequest(){
        return $this->_request;
    }

    /**
     * @return core_layout
     */
    public function initLayout(){
        $this->_layout = new core_layout();
        return $this->getLayout();
    }
    /**
     * @return core_layout
     */
    public function getLayout(){
        if( !$this->_layout){
            $this->initLayout();
        }
        return $this->_layout;
    }

    public function renderLayout(){
        $this->getLayout()->render();
    }

    /**
     * @return $this
     * @throws core_exception
     */
    public function dispatchAction( ){
        $action = $this->getRequest()->getActionName();
        $actionMethod = $action.self::ACTION_SUFFIX;
        if(!method_exists($this,$actionMethod)){
            throw new core_exception_controller("Controller action {$actionMethod} not found");
        }
        $this->_actionMethod = $actionMethod;
        $this->_predispatchAction();
        $actionMethod = $this->_actionMethod;
        $this->$actionMethod();
        return $this;
    }

    protected function _predispatchAction(){
        return $this;
    }

    protected function _redirect($url){
        header("Location: {$url}");
        die();
    }

    static function okJson($data=null){
        return json_encode(array('ok'=>true,'data'=>$data));
    }

    static function errJson($err){
        return json_encode(array('ok'=>false,'err'=>$err));
    }


}