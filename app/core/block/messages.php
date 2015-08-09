<?php
class core_block_messages extends core_block{

    public function __construct(){
        parent::__construct('page/messages.phtml');
    }

    public function getMessages(){
        return app::getSession()->getMessages();
    }

    public function seenMessage($k){
        return app::getSession()->removeMessage($k);
    }
}