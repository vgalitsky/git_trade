<?php
class core_pdo_mysql_pdo implements core_pdo_interface{

    /** @var \PDO  */
    protected $_dbh;

    /**
     * @param $config
     */
    public function __construct( $config ){
        $this->_dbh = new PDO($config['dsn'], $config['username'], $config['password'], $config['options']);
    }

    /**
     * @return PDO
     */
    public function getDbh(){
        return $this->_dbh;
    }
}