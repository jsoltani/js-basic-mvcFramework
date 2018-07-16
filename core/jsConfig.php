<?php

namespace core;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

/**
 * jsConfig
 *
 * PHP version 7.1
 */
class jsConfig {

    private static $config = null;

    /*
     * set
     * set the config file
     *
     * @param array $_config
     */
    public static function set($_config){
        self::$config = $_config;
    }

    /*
     * get
     * get config by config key
     *
     * @param string $configKey
     * return string
     */
    public static function get($configKey = ""){
        if(empty($configKey)){
            throw new \Exception("config name cannot be empty");
        }elseif ( !isset(self::$config[$configKey]) ){
            throw new \Exception("config key [$configKey] not valid");
        }
        return self::$config[$configKey];
    }
}
