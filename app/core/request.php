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
        $this->_session = new core_session( );
        $this->_server  = $this->_safe( $_SERVER );
    }

    public function getPart( $part ){
        $part = '_'.$part;
        return $this->$part;
    }
    public function getSession(){
        return $this->getPart('session');
    }

    protected function _initRequest(){

        $request_uri = $this->_server['REQUEST_URI'];
        $request_uri = preg_replace('/\?.*/','',$request_uri);
        $parts = explode('/', $request_uri);
        $this->_mod = $parts[1];
        $this->_controller = (isset($parts[2]) && $parts[2]) ? $parts[2] : core_controller::DEFAULT_CONTROLLER;
        $this->_action = (isset($parts[3]) && $parts[3]) ? $parts[3] : core_controller::DEFAULT_ACTION;
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
            if(!isset($this->_request[$var]) && isset($vals[$k])){
                $this->_request[$var] = $vals[$k];
            }
        }
        return $this;
    }

    protected function _safe( $var ){
        //@TODO safe
        return $var;
    }

    public function getParam( $param, $default = null ){
        if(isset($this->_request[$param])){
            return $this->_request[$param];
        }
        return $default;
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

    public function getReferer(){
        return $_SERVER["HTTP_REFERER"];
    }

    protected function  _url_origin($s, $use_forwarded_host=false)
    {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
        $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }
    function getRequestedUrl( $s = null, $use_forwarded_host=false)
    {
        $s = $s? $s : $this->getPart('server');
        return $this->_url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
    }



}