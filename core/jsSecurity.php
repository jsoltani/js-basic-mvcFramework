<?php

namespace core;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

use \core\jsHash;

/**
 * Security
 *
 * PHP version 7.1
 */
class jsSecurity{

    protected static $doOriginCheck = true;
	
	/**
     * jsXSSClean
	 * for clean input data
     * 
	 * @param string $data
	 *
	 * @return string
     */
	public static function jsXSSClean( string $data ) : string {
		
		// Fix &entity\n;
		$quotes = array ("\0", "\x00", "\x0A", "\x0D", "\x1A", "\x22", "\x23", "\x25", "\x26", "\x27", "\x5C", "\x2F", "\x60", "\t", '\n', '\r', "'", "\\", '"', "/", "&", "#");
		$data = str_replace($quotes, '', $data);
		
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '', $data);
		$data = trim(strip_tags(stripslashes(htmlspecialchars($data))));
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do
		{
				// Remove really unwanted tags
				$old_data = $data;
				$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);
		return $data;
	}
	
	/**
     * jsUrlClean
	 * for clean input data from url
     * 
	 * @param string $data
	 *
	 * @return string
     */
	public static function jsUrlClean( string $data ) : string{
		$quotes = array ("\0", "\x00", "\x0A", "\x0D", "\x1A", "\x22", "\x23", "\x25", "\x26", "\x27", "\x5C", "\x2F", "\x60", "\t", '\n', '\r', "'", "\\", ".", "/", "¬", "#", ";", "~", "[", "]", "{", "}", "=", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"', " ", "..", "@");
		$data = str_replace($quotes, '', $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '', $data);
		
		return $data;
	}

    /**
     * jsCheckCSRF
     * for clean input data from url
     *
     * @param string $key
     * @param string $origin
     * @param bool $throwException
     * @param int $timespan
     * @param bool $multiple
     *
     * @return bool
     * @throws \Exception
     */
	public static function jsCheckCSRF(string $key, string $origin, bool $throwException = false, int $timespan = null, bool $multiple=true) : bool {

	    if ( !jsSession::check('csrf_' . $key ) ){
            if($throwException)
                throw new \Exception( 'Missing CSRF session token.' );
            else
                return 'false1';
        }

		if ( !isset( $origin ) )
            if($throwException)
                throw new \Exception( 'Missing CSRF form token.' );
            else
                return 'false2';

        // Get valid token from session
        $hash = jsSession::get( 'csrf_' . $key );
		
        // Free up session token for one-time CSRF token usage.
		if(!$multiple)
			jsSession::set( 'csrf_' . $key, null);

        // Origin checks
        if( self::$doOriginCheck && sha1( $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] ) != substr( base64_decode( $hash ), 10, 40 ) )
        {
            if($throwException)
                throw new \Exception( 'Form origin does not match token origin.' );
            else
                return false;
        }
        
        // Check if session token matches form token
        if ( $origin != $hash )
            if($throwException)
                throw new \Exception( 'Invalid CSRF token.' );
            else
                return false;

        // Check for token expiration
        if ( $timespan != null && is_int( $timespan ) && intval( substr( jsHash::jsBase64Decode( $hash ), 0, 10 ) ) + $timespan < time() )
            if($throwException)
                throw new \Exception( 'CSRF token has expired.' );
            else
                return false;

        return true;
    }
	
	/**
     * jsGenerateCSRF
	 * generate CSRF token
     * 
	 * @param string $key
	 *
	 * @return string
     */
	public static function jsGenerateCSRF( string $key ) : string {
		$extra = self::$doOriginCheck ? sha1( $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] ) : '';
		$token = jsHash::jsBase64Encode( time() . $extra . self::jsRandomStringCSRF( 32 ) );
        jsSession::set( 'csrf_' . $key, $token);

        return $token;
    }

    /**
     * jsRandomStringCSRF
     * generate random string
     *
     * @param int $length
     *
     * @return string
     */
	private static function jsRandomStringCSRF( int $length ) : string {
        $seed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijqlmnopqrtsuvwxyz0123456789';
        $max = strlen( $seed ) - 1;

        $string = '';
        for ( $i = 0; $i < $length; ++$i )
            $string .= $seed{intval( mt_rand( 0.0, $max ) )};

        return $string;
    }
}