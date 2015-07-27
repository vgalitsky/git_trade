<?php
$___config = new core_config();
$___config->setData(array(


    /** Design */
    'design' => array(
        'skin' => 'default',
        'core' => array(
            'skin' => array(
                'path' => 'app'.DS.'core'.DS.'design'.DS.'%skin%'.DS,
            ),
        ),
        'mod' => array(
            'skin' => array(
                'path' => 'app'.DS.'mod'.DS.'%mod%'.DS.'design'.DS.'%skin%'.DS,
            ),
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

    'url' => array(
        'base' => 'http://gis.local/',
    ),


    'dir' => array(
        'base' => __DIR__,
        'sd' => __DIR__.DS.'sd'.DS,
        'log' => __DIR__.DS.'sd'.DS.'log'.DS
    ),

));
