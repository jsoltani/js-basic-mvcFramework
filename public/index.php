<?php

session_start();

define('JS', true);
define('JS_START', microtime(true));

/**
 * Composer autoload
 */
require dirname(__DIR__) . '/vendor/autoload.php';
$config   = require dirname(__DIR__) . '/config/jsConfig.php';
$language = (!isset($config['LOCALE']) || empty($config['LOCALE'])) ? 'fa' : $config['LOCALE'];
$languages = require dirname(__DIR__) . '/languages/'. $language .'.php';
\core\jsConfig::set($config);
\core\jsLanguage::set($languages);

date_default_timezone_set($config['TIMEZONE']);

define('DB_PREFIX', $config['DB_PREFIX']);
define('APP_ROOT', $config['APP_ROOT']);
define('APP_PUBLIC', $config['APP_PUBLIC']);

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('core\jsError::jsErrorHandler');
set_exception_handler('core\jsError::jsExceptionHandler');

/**
 * Routing
 */
$router = new core\jsRouter();

// Add the routes
$router->add('', ['controller' => 'home', 'action' => 'index']);  
$router->add('{controller}', ['controller' => '{controller}', 'action' => 'index']);
$router->add('{controller}/{action}'); 
$router->add('{controller}/{id:\d+}/{action}'); // sample: user/100/edit
//$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
$router->dispatch();