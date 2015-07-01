<?php
class core_collection implements IteratorAggregate, Countable{

    protected $_items;
    protected $_loaded;
    protected $_model_class;

    /** @var  PDO */
    static $connection;

    protected $_table;
    protected $_sql;
    /** @var PDOStatement $stmt */
    protected $_stmt;


    public function __construct( $model_class, $table){
        $this->_init($model_class, $table);
    }

    public function _init( $model_class,$table ){
        $this->_model_class = $model_class;
        $this->_table = $table;
        $this->_loaded = false;
        $this->prepare();
    }

    public function setConnection( $con ){
        self::$connection = $con;
        return $this;
    }

    public function getConnection(){
        if(self::$connection){
            $this->setConnection(app::getConnection());
        }
        return self::$connection;
    }

    public function setSql( $sql ){
        $this->_sql = $sql;
        return $this;
    }
    public function getSql(){
        return $this->_sql;
    }

    /**
     * @return PDOStatement
     */
    public function getStatement(){
        return $this->_stmt;
    }

    public function setStatement( $stmt){
        $this->_stmt = $stmt;
        return $this;
    }

    public function getTable(){
        return $this->_table;
    }

    public function getModelClass(){
        return $this->_model_class;
    }

    public function isLoaded( $loaded = null){
        if($loaded !== null){
            $this->_loaded = $loaded;
        }
        return $this->_loaded;
    }

    public function getIterator() {
        if(!$this->isLoaded()){
            $this->load();
        }
        return new ArrayIterator($this->_items);
    }

    public function count(){
        return count($this->_items);

    }

    public function prepare(){

        $this->_beforePrepare();
        $this->setSql( "SELECT * FROM `{$this->getTable()}`" );
        $this->_afterPrepare();
        return $this;
    }

    protected function _beforePrepare(){}
    protected function _afterPrepare(){}

    protected function _beforeLoad(){}
    protected function _afterLoad(){}

    public function load(){
        $this->_beforeLoad();
        /** @var PDOStatement $stmt */
        $this->setStatement( $this->getConnection()->prepare( $this->getSql() ) );
        $this->getStatement()->execute();
        while ($row = $this->getStatement()->fetch()){
            $model = app::getModel( $this->getModelClass() );
            $model->setData($row)->setOrigData($row);
            $this->_items[] = $model;
        }
        $this->isLoaded(true);
        $this->_afterPrepare();
        return $this;
    }





}