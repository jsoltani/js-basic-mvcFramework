<?php

namespace core;

if( !defined( 'JS') ){
    die(' Access Denied !!! ');
}

/**
 * jsSession
 *
 * PHP version 7.1
 */
class jsSession {
	
	/**
     * init
	 * session start if none
     * 
     */
	public static function init() {
        if(session_status() === PHP_SESSION_NONE || empty(session_id())){
            session_start();
        }
    }
    
	/**
     * set
	 * set session
     * 
	 * @param string $key
	 * @param string $value
	 *
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * get
     * get session value
     *
     * @param string $key
     *
     * @return string|mixed
     */
    public static function get($key) {
        if (isset($_SESSION[$key]))
            return $_SESSION[$key];
        return '';
    }

    /**
     * check
     * check session isset
     *
     * @param string $key
     *
     * @return string|mixed
     */
    public static function check($key) {
        return isset($_SESSION[$key]) ? true : false;
    }
    
	/**
     * destroy
	 * destroy all sessions
     *
     */
    public static function destroy() {
        session_destroy();
    }
	
	/**
     * unset
	 * unset session
     * 
	 * @param string $key
	 *
     */
	public static function unset($key) {
        if (isset($_SESSION[$key]))
        unset($_SESSION[$key]);
    }
	
	/**
     * regenerateId
	 * regenerate session id
     *
     */
	public static function regenerateId() {
        session_regenerate_id(true);
    }
	
}