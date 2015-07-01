<?php
class core_block_page extends core_block{

    const DEFAULT_PAGE = 'page/page-1-column.phtml';

    public function __construct( $template = null ){

        parent::__construct( self::DEFAULT_PAGE );
        $this->addChild('head', new core_block_head());
        $this->addChild('header', new core_block_header());
        $this->addChild('footer', new core_block_footer());
    }
}