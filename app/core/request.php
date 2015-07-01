<?php

class core_request {

    /**
     * @var array GET vars
     */
    protected $_get;
    /**
     * @var array POST vars
     */
    protected $_post;
    /**
     * @var array FILES vars
     */
    protected $_files;

    /**
     * @var $_REQUEST
     */
    protected $_request;

    /**
     * @var $_COOKIE
     */
    protected $_cookie;

    /**
     * @var $_SESSION
     */
    protected $_session;

    /**
     * @var SERVER
     */
    protected $_server;



    protected $_mod;
    protected $_controller;
    protected $_action;

    public function __construct(){
        $this->_init();
    }

    protected function _init(){
        $this->_initVars();
        $this->_initRequest();
        return $this;
    }

    protected function _initVars(){
        $this->_get     = $this->_safe( $_GET );
        $this->_post    = $this->_safe( $_POST );
        $this->_request = $this->_safe( $_REQUEST );
        $this->_files   = $this->_safe( $_FILES );
        $this->_cookie  = $this->_safe( $_COOKIE );
        //$this->_session = $this->_safe( $_SESSION );
        $this->_server  = $this->_safe( $_SERVER );
    }

    protected function _initRequest(){

        $request_uri = $this->_server['REQUEST_URI'];
        $request_uri = preg_replace('/\?.*/','',$request_uri);
        $parts = explode('/', $request_uri);
        $this->_mod = $parts[1];
        $this->_controller = $parts[2];
        $this->_action = (isset($parts[3]) && $parts[3]) ? $parts[3] : self::DEFAULT_ACTION.self::ACTION_SUFFIX;
        unset($parts[0],$parts[1], $parts[2],$parts[3]);
        $this->addRequestVars( $parts );
        return $this;
    }

    protected function addRequestVars( $parts ){
        reset($parts);
        $var = true;
        $vals = array();
        $vars = array();
        foreach( $parts as $part){
            if($var){
                $vars[]=$part;
                $var = false;
            }else{
                $vals[]=$part;
                $var = true;
            }
        }

        foreach($vars as $k=>$var){
            if(!isset($this->_get[$var]) && isset($vals[$k])){
                $this->_get[$var] = $vals[$k];
            }
        }
        return $this;
    }

    protected function _safe( $var ){
        //@TODO safe
        return $var;
    }

    public function getControllerName( ){
        return $this->_controller;
    }

    public function getActionName(){
        return $this->_action;
    }

    public function getModule(){
        return $this->_mod;
    }




}