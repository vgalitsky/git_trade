<?php
class core_db {
    /** @var  PDO */
    static $connection;

    public function __construct(){
        $this->setConnection( app::getConnection() );
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

    public function sqlFetchAll( $sql, $vars = array(), $fetch_type = PDO::FETCH_ASSOC ){
        return $this->sqlExec($sql,$vars)
            ->fetchAll($fetch_type);
    }

    public function sqlFetch( $sql, $vars = array(), $fetch_type = PDO::FETCH_ASSOC ){
        return $this->sqlExec($sql,$vars)
            ->fetch($fetch_type);
    }

    public function sqlExec( $sql, $vars = array() ){
        $stmt = $this->getConnection()->prepare( $sql );
        $stmt->execute($vars);
        return $stmt;
    }

}