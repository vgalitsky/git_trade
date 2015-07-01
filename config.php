<?php
$___config = new core_config();
$___config->setData(array(


    /** Design */
    'design' => array(
        'skin' => 'default',
        'core' => array(
            'template' => array(
                'path' => 'app'.DS.'core'.DS.'design'.DS.'%skin%'.DS.'template'.DS,
            )
        ),
        'mod' => array(
            'template' => array(
                'path' => 'app'.DS.'mod'.DS.'%mod%'.DS.'design'.DS.'%skin%'.DS.'template'.DS,
            )
        )
    ),
    'pdo' => array(
        'adapter' => 'mysql',
        'config'  => array(
            'dsn' => 'mysql:host=localhost;dbname=gis_trade',
            'username' => 'root',
            'password' => 'smxksmxk',
            'options'  => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            )
        )
    ),
));
