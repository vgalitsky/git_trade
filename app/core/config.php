<?php
class core_config{

    protected $_config;

    public function etConfig($config, $value){
        $this->_config[$config] = $value;
        return $this;
    }

    public function getConfig( $config ){
        if(strstr($config,'/')){
            return $this->getConfigByPath( $config );
        }
        return isset($this->_config[$config]) ? $this->_config[$config] : null;
    }

    public function getConfigByPath( $path ){
        $path_array = explode('/', $path);
        $conf_val = $this->_config;
        foreach( $path_array as $token){
            $conf_val = (is_array($conf_val) && isset($conf_val[$token])) ? $conf_val[$token] : null;
        }
        return $conf_val;
    }

    public function setData($config_data){
        $this->_config = $config_data;
        return $this;
    }
}