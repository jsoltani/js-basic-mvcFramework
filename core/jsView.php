<?php

namespace core;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

/**
 * View
 *
 * PHP version 7.1
 */
class jsView {

    /**
     * Render a view file
     *
     * @param string $view The view file
     * @param array $args Associative array of data to display in the view (optional)
     * @param bool $header
     *
     * @return void
     * @throws \Exception
     */
    public static function render($view, $args = [], $header = true) {
        extract($args, EXTR_SKIP);

        $_header = dirname(__DIR__) . "/app/views/header.php";  // relative to core directory
        $_footer = dirname(__DIR__) . "/app/views/footer.php";  // relative to core directory
        $_file   = dirname(__DIR__) . "/app/views/$view";  // relative to core directory

        if (is_readable($_file)) {
            if($header and file_exists($_header)){
                require $_header;
            }
            require $_file;
            if($header and file_exists($_footer)){
                require $_footer;
            }
        } else {
            throw new \Exception("$_file not found");
        }
    }
}
