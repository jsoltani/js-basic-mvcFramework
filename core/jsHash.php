<?php

namespace core;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

class jsHash {

    /**
     * create
     * for create hash with algorithm
     *
     * @param string $algo
     * @param string $data
     * @param string $salt
     *
     * @return string
     */
	public static function create( string $algo, string $data, string $salt) : string {
    	$context = hash_init($algo, HASH_HMAC, $salt);
        hash_update($context, $data);
        return hash_final($context);
    }
	
	/**
     * jsNewEncrypt
	 * for encrypt data
     * 
	 * @param string $ENTEXT
	 *
	 * @return string
     */
	public static function jsNewEncrypt( string $ENTEXT ) : string { 
		$encrypt = serialize($ENTEXT);
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
		$key = pack('H*', ENCRYPTION_KEY);
		$mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
		$passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
		$encoded = self::jsBase64Encode($passcrypt).'|'.self::jsBase64Encode($iv);
		return $encoded;
	}
	
	/**
     * jsNewDecrypt
	 * for decrypt data
     * 
	 * @param string $DETEXT
	 *
	 * @return string
     */
	public static function jsNewDecrypt( string $DETEXT ) : string {
		$decrypt = explode('|', $DETEXT.'|');
		$decoded = self::jsBase64Decode($decrypt[0]);
		$iv = self::jsBase64Decode($decrypt[1]);
		if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
		$key = pack('H*', ENCRYPTION_KEY);
		$decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
		$mac = substr($decrypted, -64);
		$decrypted = substr($decrypted, 0, -64);
		$calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
		if($calcmac!==$mac){ return false; }
		$decrypted = unserialize($decrypted);
		return $decrypted;
	}
	
	/**
     * Encrypt
	 * for encrypt data
     * 
	 * @param string $ENTEXT
	 *
	 * @return string
     */
	public static function Encrypt( string $ENTEXT ) : string { 
		$randomNum = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
		return $randomNum . trim(self::jsBase64Encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, KEY,
        	$ENTEXT, MCRYPT_MODE_ECB, mcrypt_create_iv(
        	mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB),MCRYPT_RAND)))); 
    } 
	
	/**
     * Decrypt
	 * for decrypt data
     * 
	 * @param string $DETEXT
	 *
	 * @return string
     */
	public static function Decrypt( string $DETEXT ) : string {
		$DETEXT = substr($DETEXT,20);
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, KEY,
       		self::jsBase64Decode($DETEXT), MCRYPT_MODE_ECB, 
       		mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB), MCRYPT_RAND))); 
    } 
	
	/**
     * jsEncrypt
	 * for encrypt data
     * 
	 * @param string $data
	 *
	 * @return string
     */
	public static function jsEncrypt( string $data ) : string {
	
		$salt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
		$rand = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 26);
	
		$key = md5(KEY . $salt, true);
		$iv  = md5($key . KEY . $salt, true);
	
		$ct = trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv));
	
		return self::jsBase64Encode($rand . $salt . $ct);
	}
	
	/**
     * jsDecrypt
	 * for decrypt data
     * 
	 * @param string $data
	 *
	 * @return string
     */
	public static function jsDecrypt( string $data ) : string {
		$data = self::jsBase64Decode($data);
		$salt = substr($data, 26, 20);
    	$ct   = substr($data, 46);
	
		$key = md5(KEY . $salt, true);
		$iv  = md5($key . KEY . $salt, true);
	
		$pt = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $ct, MCRYPT_MODE_ECB, $iv));
	
		return $pt;
	}    
	
	/**
     * jsBase64Encode
	 * for encode base64 data
     * 
	 * @param string $s
	 *
	 * @return string
     */
	public static function jsBase64Encode( string $s ) : string {
		return rtrim(str_replace(array('+', '/'), array('-', '_'), base64_encode($s)), '=');
	}
	
	/**
     * jsBase64Decode
	 * for decode base64 data
     * 
	 * @param string $s
	 *
	 * @return string
     */
	public static function jsBase64Decode( string $s ) : string {
		return base64_decode(str_replace(array('-', '_'), array('+', '/'), $s));
	}
}