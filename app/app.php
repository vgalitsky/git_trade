<?php
class app{

    /** @var \core_request  */
    static $_request;

    /** @var  core_config  */
    static $_config;

    /** @var   */
    static $_pdo;

    public function __construct( $_config ){
        self::$_config = $_config;
        self::$_request = new core_request();
        self::$_pdo = core_pdo::getAdapter( $this->getConfig('pdo/adapter'), $this->getConfig('pdo/config') );
    }

    /**
     *
     */
    public function run(){
        $this->_dispatchController();
    }

    /**
     * @return core_request
     */
    static function getRequest(){
        return self::$_request;
    }

    /**
     * @return PDO
     */
    static function getConnection(){
        return self::$_pdo->getDbh();
    }

    /**
     *
     */
    protected function _dispatchController(){
        $controller = $this->getController();
        $controller->dispatchAction();
    }

    /**
     * @return core_controller
     */
    public function getController(){
        $ctrl_class = self::getRequest()->getModule() . CS .core_controller::DIR .CS. self::getRequest()->getControllerName();
        if(!class_exists($ctrl_class)){
            throw new Exception("Cannot find controller '$ctrl_class'");
        }
        $controller = new $ctrl_class();
        $controller->setRequest( self::getRequest() );
        return $controller;
    }

    /**
     * @param null $config
     * @return core_config|null
     */
    static function getConfig( $config = null ){
        if(!$config){
            return self::$_config;
        }
        return self::$_config->getConfig( $config );
    }

    /**
     * @param $model
     * @param $param
     * @return core_model
     */
    static function getModel( $model, $param=null ){
        if(!class_exists($model)){
            throw new Exception("Cannot find model '{$model}'");
        }
        $model = new $model( $param );
        $model->setConnection( self::getConnection() );
        return $model;
    }



}