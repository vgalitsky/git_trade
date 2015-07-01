<?php
class core_controller {
    const DIR = 'controller';

    const DEFAULT_ACTION = 'index';
    const ACTION_SUFFIX = 'Action';

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
     * @throws Exception
     */
    public function dispatchAction( ){
        $action = $this->getRequest()->getActionName();
        $actionMethod = $action.self::ACTION_SUFFIX;
        if(!method_exists($this,$actionMethod)){
            throw new Exception("Controller action {$actionMethod} not found");
        }
        $this->$actionMethod();
        return $this;
    }
}