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

    public function getLoggedInUser( $model = null){
        if(!$this->getData('suid')){
            return false;
        }
        if(!self::$logged_in_user){
            if(!$model){
                $model = new core_model_user();
            }elseif( is_string($model)){
                $model = new $model();
            }
            $model = new core_model_user();
            $user = $model;
            $user->load($this->getData('suid'),'suid');
            self::$logged_in_user = $user;
        }
        return self::$logged_in_user;
    }

    public function addMessage($message){
        $messages = $this->getData('_session_messages');
        $messages = is_array($messages) ? $messages : array();
        $messages[]=$message;
        $this->setData('_session_messages',$messages);
        return $this;
    }

    public function removeMessage($k){
        $messages = $this->getData('_session_messages');
        $msg = $messages[$k];
        unset($messages[$k]);
        $this->setData( '_session_messages', $messages );
        return $msg;
    }

    public function getMessages(){
        return $this->getData('_session_messages') ? $this->getData('_session_messages') : array();
    }

}