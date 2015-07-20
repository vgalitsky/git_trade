<?php
class manage_block_user_addform extends manage_block_template{

    public function __construct(){
        parent::__construct('user/addform.phtml');
    }

    protected function _initVars(){
        $manager_model = new manage_model_user();
        /** @var manage_model_user_collection $manager_collection */
        $manager_collection = $manager_model->getCollection();
        $manager_collection->addRoleFilter(manage_model_role::ROLE_MANAGER);
        $this->setVar('managers',$manager_collection);
        return $this;
    }
}