<?php
class core_model extends core_object{

    /** @var  PDO */
    static $connection;
    /** @var  array */
    protected $_data;
    /** @var  array */
    protected $_origData;
    /** @var  string */
    protected $_table;
    /** @var  string */
    protected $_idfield;
    /** @var  array */
    static $_describe;

    /**
     * @param null $table
     * @param null $idfield
     */
    public function __construct( $table = null, $idfield = null){
        $this->setConnection( app::getConnection() );
        if($table){
            $this->_init( $table, $idfield);
        }
    }

    /**
     * @param string $table
     * @param string $idfield
     * @return $this
     */
    protected function _init( $table, $idfield = 'id' ){
        $this->_table = $table;
        $this->_idfield = $idfield;
        $this->describe();
        return $this;
    }

    /**
     * @return string
     */
    public function getIdField(){
        return $this->_idfield;
    }

    /**
     * @return string
     */
    public function getTable(){
        return $this->_table;
    }

    /**
     * @return array|null
     */
    public function getId(){
        return $this->getData( $this->getIdField());
    }

    /**
     * @return array
     */
    public function describe(){
        if(!self::$_describe){
            $sql = "DESCRIBE {$this->_table}";
            /** @var PDOStatement $stmt */
            $stmt = $this->getConnection()->prepare( $sql );
            $stmt->execute();
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach($columns as $column){
                self::$_describe[$column] = true;
            }
        }
        return self::$_describe;
    }

    /**
     * @param PDO $connection
     * @return $this
     */
    public function setConnection($connection){
        self::$connection = $connection;
        return $this;
    }

    /**
     * @return PDO
     */
    public function getConnection(){
        return self::$connection;
    }



    /**
     * @param $var
     * @param $val
     * @return $this
     */
    public function setData( $var , $val =null){
        if(!$val){
            $this->_data = $var;
            return $this;
        }
        $this->_data[$var] = $val;
        return $this;
    }

    /**
     * @param null $var
     * @return array|null
     */
    public function getData( $var = null ){
        if(!$var) {
            return $this->_data;
        }
        return isset($this->_data[$var]) ? $this->_data[$var] : null;
    }

    public function setOrigData($data){
        $this->_origData = $data;
        return $this;
    }

    public function getOrigData(){
        return $this->_origData;
    }

    protected function _beforeSave(){ return $this;}
    protected function _afterSave(){return $this;}

    /**
     * @return $this
     */
    public function save(){
        $this->_beforeSave();
        if($this->getId()){
            $this->_saveExists();
        }else{
            $this->_create();
        }
        $this->_afterSave();
        return $this;
    }

    /**
     * @return $this
     */
    protected function _saveExists(){
        if(!$this->hasChanges()){
            return $this;//has no changes
        }
        $values = array();
        $set = '';
        foreach( $this->getData() as $field=>$value){
            if($field === $this->getIdField()){
                continue;//no need to change id
            }
            if($this->hasField($field)){
                if(!$this->isFieldChanged($field)){
                    continue;//no need to change not modified field
                }
                $set.="`{$field}`=:{$field}, ";
                $values[$field]=$value;
            }
        }
        $set = substr($set,0,-2);
        $values['id']=$this->getId();
        $sql = "UPDATE {$this->getTable()} SET {$set} WHERE {$this->getIdField()}=:id";
        /** @var PDOStatement $stmt */
        $stmt = $this->getConnection()->prepare( $sql );
        $stmt->execute($values);
        return $this;
    }

    /**
     * @return $this
     */
    protected function _create(){
        $values = array();
        $set = '';
        foreach( $this->getData() as $field=>$value){
            if($field === $this->getIdField()){
                continue;
            }
            if(!$this->hasField($field)){
                continue;
            }
            $set.="`{$field}`=:{$field}, ";
            $values[$field]=$value;
        }
        $set = substr($set,0,-2);
        $sql = "INSERT INTO {$this->getTable()} SET {$set} ";
        /** @var PDOStatement $stmt */
        $stmt = $this->getConnection()->prepare( $sql );
        $stmt->execute($values);
        return $this;
    }


    /**
     * @return bool
     */
    public function hasChanges(){
        foreach($this->_origData as $field=>$value){
            if( $value !== $this->getData($field) ){
                return true;
            }
        }
        return false;
    }

    /**
     * @param $field
     * @return bool
     */
    public function isFieldChanged( $field ){
        if($this->getData($field) === $this->_origData[$field]){
            return false;
        }
        return true;
    }

    /**
     * @param $field
     * @return bool
     */
    public function hasField( $field ){
        return isset($this->_describe[$field]);
    }

    protected function _beforeLoad( $value, $field){return $this;}
    protected function _afterLoad( $value, $field){return $this;}

    /**
     * @param $value
     * @param null $field
     * @return $this
     */
    public function load( $value, $field=null){
        $field = $field ? $field : $this->getIdField();
        $this->_beforeLoad($value, $field);

        $sql = "SELECT * FROM {$this->getTable()} WHERE {$field}=:value";
        /** @var PDOStatement $stmt */
        $stmt = $this->getConnection()->prepare( $sql );
        $stmt->execute(array('value'=>$value));
        /** @var array $data */
        $data = $stmt->fetch();

        //do not use numeric index
        foreach( $data as $k=>$value){
            if(is_numeric($k)){
                unset($data[$k]);
            }else{
                $this->setData($k,$value);
            }
        }
        $this->setOrigData( $data );

        $this->_afterLoad($value, $field);
        return $this;
    }

    public function getCollection(){
        $collection_class = get_class($this).'_collection';
        $collection = new $collection_class(get_class($this),$this->getTable());
        $collection->setConnection($this->getConnection());
        return $collection;
    }

}