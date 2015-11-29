<?php
class core_db_select{

    const PART_SELECT   = 'select';
    const PART_FROM     = 'from';
    const PART_COLUMNS  = 'columns';
    const PART_JOIN     = 'join';
    const PART_WHERE    = 'where';
    const PART_ORDER    = 'order';
    const PART_GROUP    = 'group';


    protected $_parts = array(

        self::PART_SELECT   => array(),
        self::PART_FROM     => array(),
        self::PART_COLUMNS  => array(),
        'join' => array(),
        'where' => array(),
        'order' => array(),
        'group' => array(),
    );

    public function __construct(){

    }


    public function getPart( $part ){


        return $this->_parts[$part];
    }

    public function addPart( $part, $value ){
        return $this->_parts[$part][] = $value;
    }

    public function order( $order ){
        return $this->addPart('order', $order);
    }

    public function columns( $columns ){
        if(!is_array($columns)){
            $columns = array($columns => $columns);
        }
        $this->addPart('columns',$columns);
    }

    public function buildSelect(){
        $sql = ' SELECT ';


    }

    public function buildColumns(){
        $columns = '';
        foreach( $this->getPart('columns') as $as => $column){
            $columns[]= "{$column} as {$as}"
        }
    }


}