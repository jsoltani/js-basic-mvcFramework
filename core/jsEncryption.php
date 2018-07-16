<?php

namespace core;

if( !defined( 'JS') ){
    die(' Access Denied !!! ');
}

use MiladRahimi\PhpCrypt\Crypt;
use MiladRahimi\PhpCrypt\Hash;

class jsEncryption {

    /**
     * password
     * make a password
     *
     * @param string $data
     * @return string
     */
    public static function password( string $data = "" ) : string {
        return Hash::make($data);
    }

    /**
     * verify
     * verify password
     *
     * @param string $password
     * @param string $stored__password
     * @return string
     */
    public static function verify( string $password = "", string $stored__password = "" ) : string {
        return Hash::verify($password, $stored__password);
    }

    /**
     * encrypt
     * encrypt data
     *
     * @param string $data
     * @return string
     * @throws \Exception
     */
	public static function encrypt( string $data = "" ) : string {
        $crypt = new Crypt(jsConfig::get('APP_KEY'));
        $crypt->setMethod(jsConfig::get('CIPHER'));
        return $crypt->encrypt($data);
	}

    /**
     * decrypt
     * decrypt data
     *
     * @param string $data
     *
     * @return string
     * @throws \Exception
     */
    public static function decrypt( string $data = "" ) : string {
        $crypt = new Crypt(jsConfig::get('APP_KEY'));
        $crypt->setMethod(jsConfig::get('CIPHER'));
        return $crypt->decrypt($data);
    }
}