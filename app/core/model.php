<?php
class core_model extends core_db{


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

    static $___id_field;

    /**
     * @param null $table
     * @param null $idfield
     */
    public function __construct( $table = null, $idfield = null){
        parent::__construct();
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
        self::$___id_field = $idfield;
        $this->describe();
        return $this;
    }

    /**
     * @return string
     */
    public function getIdField(){
        return $this->_idfield;
    }

    static function ___getIdField(){
        return self::$___id_field;
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
        return $this->getData( $this->getIdField() );
    }

    /**
     * @param $id
     */
    public function setId($id){
        $this->setData($this->getIdField(),$id);
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

    public function unsetData($var){
        if(isset($this->_data[$var])){
            unset($this->_data[$var]);
        }
        return $this;
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
        if($set===''){
            return $this;
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
        $this->setId($this->getConnection()->lastInsertId());
        $this->setOrigData($this->getData());
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
        $describe = self::$_describe;
        return isset($describe[$field]);
    }

    public function _beforeLoad( ){return $this;}
    public function _afterLoad( ){return $this;}

    /**
     * @param $value
     * @param null $field
     * @return $this
     */
    public function load( $value, $field=null){
        if(!$value){ return $this;}
        $field = $field ? $field : $this->getIdField();
        $this->_beforeLoad($value, $field);

        $sql = "SELECT * FROM {$this->getTable()} WHERE {$field}=:value";
        /** @var PDOStatement $stmt */
        $stmt = $this->getConnection()->prepare( $sql );
        $stmt->execute(array('value'=>$value));
        /** @var array $data */
        $data = $stmt->fetch();

        //do not use numeric index
//        foreach( $data as $k=>$value){
//            if(is_numeric($k)){
//                unset($data[$k]);
//            }else{
//                $this->setData($k,$value);
//            }
//        }
        $this->setData( $data );
        $this->setOrigData( $data );

        $this->_afterLoad();
        return $this;
    }

    public function delete(){
        if(!$this->getId()){
            throw new Exception('Id was not given');
        }
        $sql = "DELETE FROM {$this->getTable()} WHERE {$this->getIdField()}=:value";
        /** @var PDOStatement $stmt */
        $stmt = $this->getConnection()->prepare( $sql );
        $stmt->execute(array('value'=>$this->getId()));
        return $this;
    }

    public function getCollection(){
        $collection_class = get_class($this).'_collection';
        if(!class_exists($collection_class)){
            $collection_class = 'core_collection';
        }
        $collection = new $collection_class(get_class($this),$this->getTable());
        $collection->setConnection($this->getConnection());
        return $collection;
    }


}