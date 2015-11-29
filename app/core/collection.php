<?php
class core_collection implements IteratorAggregate, Countable{

    protected $_items;
    protected $_loaded;
    protected $_model_class;
    protected $_parts;


    /** @var  PDO */
    static $connection;

    protected $_table;
    protected $_sql;
    /** @var PDOStatement $stmt */
    protected $_stmt;

    protected $_sql_values;


    public function __construct( $model_class, $table){
        $this->_items = array();
        $this->_init($model_class, $table);
    }

    public function _init( $model_class,$table ){
        $this->_model_class = $model_class;
        $this->_table = $table;
        $this->_loaded = false;
        $this->prepareSql();
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

    public function getIdField(){
        $mc = $this->_model_class;
        return $mc::___getIdField();
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

    public function prepareSql(){
        $this->setSql( "SELECT * FROM `{$this->getTable()}` as main_table" );
        return $this;
    }

    public function setSqlValue($var, $value){
        $this->_sql_values[$var] = $value;
        return $this;
    }

    public function getSqlValues(){
      return $this->_sql_values;
    }

    public function getSqlValue($var){
        return $this->_sql_values[$var];
    }


    protected function _beforeLoad(){}
    protected function _afterLoad(){}
    protected function _beforeLoadItemData($data,$item){return $data;}
    protected function _afterLoadItemData($item){return $item;}

    public function load(){
        $this->_beforeLoad();
        /** @var PDOStatement $stmt */
        $this->setStatement( $this->getConnection()->prepare( $this->getSql() ) );
        $this->getStatement()->execute( $this->getSqlValues() );
        while ($row = $this->getStatement()->fetch(PDO::FETCH_ASSOC)){
            $model = app::getModel( $this->getModelClass() );
            $row = $this->_beforeLoadItemData($row, $model);
            $model->_beforeLoad();
            $model->setData($row)->setOrigData($row);
            $model->_afterLoad();
            $this->_afterLoadItemData($model);
            $this->_items[] = $model;
        }
        $this->isLoaded(true);
        $this->_afterLoad();
        return $this;
    }

    public function addColumnFilter($column,$value, $cond='='){
        $uniqidval = uniqid('val_');
        $sql = $this->getSql();
        if(!stristr($sql,'where')){
            $sql .= "WHERE 1";
        }
        $sql.= "AND ({$column} {$cond} :{$uniqidval})";
        $this->setSqlValue($uniqidval, $value);
        $this->setSql( $sql );
        return $this;
    }

    /**
     * @param bool|true $use_id_as_key
     * @return array
     */
    public function toArray( $use_id_as_key = true){
        $array = array();
        foreach($this as $item){
            if($use_id_as_key) {
                $array[$item->getId()] = $item->getData();
            }else{
                $array[] = $item->getData();
            }
        }
        return $array;
    }







}