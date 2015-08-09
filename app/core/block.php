<?php

class core_block
{

    const DEFAULT_SKIN = 'default';

    /** @var  array */
    protected $_template;
    /** @var  array */
    protected $_vars;
    /** @var  array( core_block ) */
    protected $_children;

    /**
     * @param null $template
     */
    public function __construct($template = null)
    {
        if ($template) {
            $this->setTemplate($template);
        }
        $this->_init();
    }

    /**
     * @param null $template
     */
    protected function _init()
    {

        $this->_initVars();
    }

    /**
     *
     */
    protected function _initVars()
    {
        return $this;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->_template = $template;
        return $this;
    }

    /**
     * @return array
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * @param $var
     * @param $value
     * @return $this
     */
    public function setVar($var, $value)
    {
        $this->_vars[$var] = $value;
        return $this;
    }

    /**
     * @param $var
     * @return null
     */
    public function getVar($var)
    {
        return isset($this->_vars[$var]) ? $this->_vars[$var] : null;
    }

    public function v($var){
        return $this->getVar($var);
    }

    /**
     * @param string $block_name
     * @param core_block $block
     * @return $this
     */
    public function addChild($block_name, $block)
    {
        $this->_children[$block_name] = $block;
        return $this;
    }

    /**
     * @param $block_name
     * @return $this
     */
    public function removeChild($block_name)
    {
        unset($this->_children[$block_name]);
        return $this;
    }

    /**
     * @param $block_name
     * @return core_block
     */
    public function getChild($block_name)
    {
        return isset($this->_children[$block_name]) ? $this->_children[$block_name] : new core_block();
    }

    /**
     * @param $block_name
     */
    public function renderChildHtml($block_name)
    {
        return $this->getChild($block_name)->renderHtml();
    }


    public function renderHtml()
    {
        $template = $this->getTemplatePath();
        if ($template) {
            include $template;
        }
    }

    public function getHtml()
    {
        ob_start();
        $this->renderHtml();
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function getChildHtml($block_name)
    {
        ob_start();
        $this->renderChildHtml($block_name);
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function getTemplatePath()
    {
        $path = realpath(self::getSkinDir('template' . DS . $this->getTemplate()));
        return is_file($path) ? $path : null;
    }

    static function parseVars($path, $vars)
    {
        $path = core_str::applyVars($path, $vars);
        return $path;
    }

    public function l($string){
        return $string;
    }

    static function getSkinDir($item, $mod = null)
    {
        if (self::getMod() !== 'core') {
            $path = self::parseVars(app::getConfig('design/mod/skin/path') . $item, array(
                'skin' => app::getConfig('design/skin'),
                'mod' => $mod ? $mod : self::getMod(),
            ));
            if (is_file($path)) {
                return $path;
            }
        }
        $path = self::parseVars(app::getConfig('design/core/skin/path') . $item, array(
            'skin' => app::getConfig('design/skin'),
            'mod' => $mod ? $mod : self::getMod(),
        ));
        return $path;
    }

    static function getSkinUrl($item, $mod = null)
    {
        if (preg_match('/http(s?):\/\//', $item)) {
            return $item;
        }

        if (self::getMod() !== 'core') {
            $path = self::parseVars(app::getConfig('design/mod/skin/path') . $item, array(
                'skin' => app::getConfig('design/skin'),
                'mod' => $mod ? $mod : self::getMod(),
            ));
            if (is_file($path)) {
                return self::getBaseUrl($path);
            }
        }
        $path = self::parseVars(app::getConfig('design/core/skin/path') . $item, array(
            'skin' => app::getConfig('design/skin'),
            'mod' => $mod ? $mod : self::getMod(),
        ));
        return self::getBaseUrl($path);

    }

    static function getBaseUrl($item = null)
    {
        return app::getConfig('url/base') . ($item ? ($item) : '');
    }

    static function getUrl($url)
    {
        return self::getBaseUrl($url);
    }


    static function getMod()
    {
        $class = get_called_class();
        $mod = substr($class, 0, strpos($class, '_block'));
        return $mod;
    }


}