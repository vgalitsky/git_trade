<?php
class core_block{

    const DEFAULT_SKIN = 'default';

    /** @var  array */
    protected $_template;
    /** @var  array */
    protected $_vars;
    /** @var  array( core_block )*/
    protected $_children;

    /**
     * @param null $template
     */
    public function __construct( $template = null ){
        if($template){
            $this->setTemplate($template);
        }
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate( $template ){
        $this->_template = $template;
        return $this;
    }

    /**
     * @return array
     */
    public function getTemplate(){
        return $this->_template;
    }

    /**
     * @param $var
     * @param $value
     * @return $this
     */
    public function setVar( $var, $value){
        $this->_vars[$var] = $value;
        return $this;
    }

    /**
     * @param $var
     * @return null
     */
    public function getVar( $var ){
        return isset($this->_vars[$var]) ? $this->_vars[$var] : null;
    }

    /**
     * @param string $block_name
     * @param core_block $block
     * @return $this
     */
    public function addChild( $block_name, $block ){
        $this->_children[$block_name] = $block;
        return $this;
    }

    /**
     * @param $block_name
     * @return core_block
     */
    public function getChild( $block_name ){
        return isset( $this->_children[$block_name] ) ? $this->_children[$block_name] : new core_block();
    }

    /**
     * @param $block_name
     */
    public function renderChildHtml( $block_name ){
        return $this->getChild( $block_name )->renderHtml();
    }


    public function renderHtml(){
        $template = $this->getTemplatePath();
        if($template){
            include $template;
        }
    }

    public function getTemplatePath(){
        $mod = $this->getMod();
        if($mod != 'core'){
            $path = $this->parsePath(app::getConfig('design/mod/template/path') . $this->getTemplate());
        }
        if($mod == 'core' || !is_file( $path )){
            $path = $this->parsePath(app::getConfig('design/core/template/path') . $this->getTemplate());
        }
        return is_file( $path ) ? $path : null;
    }

    public function parsePath( $path ){
        $path = core_str::applyVars( $path, array(
            'skin' => app::getConfig('design/skin'),
            'mod' => $this->getMod(),
        ) );
        return $path;
    }


    public function getMod(){
        $class = get_class($this);
        $mod = substr($class,0,strpos($class,'_block'));
        return $mod;
    }


}