<?php
class core_block_footer extends core_block{
    public function __construct($template = null){
        $this->setTemplate('page/footer.phtml');
    }
}