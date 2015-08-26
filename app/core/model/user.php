<?php
class core_model_user extends core_model
{


    public function __construct()
    {
        parent::__construct('user', 'user_id');
    }

    public function authenticate($username, $password)
    {

        $sql = "SELECT * FROM {$this->getTable()} WHERE username = ? AND (password=MD5(?) OR ( role_id=? AND password=''))";
        try {
            $user = $this->sqlFetch($sql, array($username, $password, manage_model_role::ROLE_MANAGER));
        }catch(Exception $e){

            die($e->getMessage());
        }
        if (!$user || !$user[$this->getIdField()]) {
            return false;
        }

        $user_model = new core_model_user();
        $user_model->load($user[$this->getIdField()]);
        $suid = md5(uniqid());
        $user_model->setData('suid', $suid);
        $user_model->save();
        app::getSession()->setData('suid',$user_model->getData('suid'));
        return true;

    }

    public function isLoggedIn(){

    }

    public function _beforeSave(){
        if($this->getData('password_repeat')){
            if($this->getData('password_repeat')!==$this->getData('password')){
                throw new core_exception('Passwords differs');
            }
            $this->setData('password',md5($this->getData('password')));
        }else{
            $this->unsetData('password');
        }
        return $this;
    }
}