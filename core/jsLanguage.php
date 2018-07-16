<?php

namespace core;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

/**
 * jsLanguage
 *
 * PHP version 7.1
 */
class jsLanguage {

    private static $language = null;

    /*
     * set
     * set the language file
     *
     * @param array $_language
     */
    public static function set($_language){
        self::$language = $_language;
    }

    /*
     * get
     * get language by language key
     *
     * @param string $languageKey
     * return string
     */
    public static function get($languageKey = ""){
        if(empty($languageKey)){
            throw new \Exception("language name cannot be empty");
        }elseif ( !isset(self::$language[$languageKey]) ){
            throw new \Exception("language key [$languageKey] not valid");
        }
        return self::$language[$languageKey];
    }
}
