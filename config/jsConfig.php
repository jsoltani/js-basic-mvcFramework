<?php 

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

use core\jsEnvironment;

return [

	/* main config */
	'APP_NAME' => jsEnvironment::env('APP_NAME', 'JS Framework'),
	'APP_URL' => jsEnvironment::env('APP_URL', 'http://localhost/js-basic-mvcFramework/'),
	'APP_FTP_URL' => jsEnvironment::env('APP_FTP_URL', ''),
	'APP_STORAGE_URL' => jsEnvironment::env('APP_STORAGE_URL', 'http://localhost/js-basic-mvcFramework/storage/'),

	'APP_ROOT' => jsEnvironment::env('APP_ROOT', '/js-basic-mvcFramework/'),
	'APP_PUBLIC' => jsEnvironment::env('APP_PUBLIC', '/js-basic-mvcFramework/public/'),
	'APP_ROOT_PATH' => dirname(__DIR__),
	'APP_STORAGE' => dirname(__DIR__) . '/storage/',
	'APP_LOG_PATH' => dirname(__DIR__) . '/storage/logs/',
	'APP_ERROR_PATH' => dirname(__DIR__) . '/storage/errors/',
	'APP_EXCEPTION_PATH' => dirname(__DIR__) . '/storage/exception/',
	'APP_DATABASE_PATH' => dirname(__DIR__) . '/storage/database/',
	'APP_KEY' => jsEnvironment::env('APP_KEY', 'C5730EA41047A939568670FC665204457B651B719E3BBFA74182A327CED07348'),
	'CIPHER' => 'AES-256-CBC',
	'TIMEZONE' => 'Asia/Tehran',
	'LOCALE' => 'fa',
	'LOGIN_WRONG' => '5',
	'LOGIN_RETRY_AGAIN' => '2',

	/* database config */
	'DB_CONNECTION' => jsEnvironment::env('DB_CONNECTION', 'mysql'),
	'DB_HOST' => jsEnvironment::env('DB_HOST', 'localhost'),
	'DB_DATABASE' => jsEnvironment::env('DB_DATABASE', 'test'),
	'DB_USERNAME' => jsEnvironment::env('DB_USERNAME', 'root'),
	'DB_PASSWORD' => jsEnvironment::env('DB_PASSWORD', ''),
	'DB_PORT' => jsEnvironment::env('DB_PORT', '3306'),
	'DB_PREFIX' => jsEnvironment::env('DB_PREFIX', 'js_'),

	/* cache config*/
	'CACHE_DRIVER' => jsEnvironment::env('CACHE_DRIVER', 'file'),
	'CACHE_FILE' => dirname(__DIR__) . '/storage/cache/',

	/* error config */
	'SHOW_ERRORS_DEVELOP' => true,
	'SHOW_ERRORS_USER' => false,

	/* log config */
	'PDO_LOG' => true,
	'SLOW_QUERY' => 0.50,

];