<?php

class core_session extends core_object{

    static $logged_in_user;

    /**
     * @param array $data
     */
    public function __construct(  ){
        $this->start();
        $this->update();
    }

    public function start(){
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            if (session_status()==PHP_SESSION_NONE) { session_start(); }
        } else {
            if(session_id()=='') { session_start(); }
        }
        return $this;
    }

    public function getData( $var=null, $default = null ){
        if(!$var){
            return $_SESSION;
        }
        return isset($_SESSION[$var]) ? $_SESSION[$var] : $default;
    }

    public function setData( $var, $value ){
        $_SESSION[$var] = $value;
        $this->update();
        return $this;
    }

    public function unsetData($var){
        unset($_SESSION[$var]);
        $this->update();
        return $this;
    }

    public function update(){
        //session_encode();
        return $this;
    }

    public function destroy(){
        session_destroy();
    }

    public function getLoggedInUser(){
        if(!$this->getData('suid')){
            return false;
        }
        if(!self::$logged_in_user){
            $user = new core_model_user();
            $user->load($this->getData('suid'),'suid');
            self::$logged_in_user = $user;
        }
        return self::$logged_in_user;
    }

}