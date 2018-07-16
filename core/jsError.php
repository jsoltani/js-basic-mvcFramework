<?php

namespace core;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

/**
 * Error and exception handler
 *
 * PHP version 7.1
 */
class jsError {

    /**
     * Error handler. Convert all errors to Exceptions by throwing an ErrorException.
     *
     * @param int $level Error level
     * @param string $message Error message
     * @param string $file Filename the error was raised in
     * @param int $line Line number in the file
     *
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function jsErrorHandler($level, $message, $file, $line) {

        //if (error_reporting() !== 0) {  // to keep the @ operator working
            //throw new \ErrorException($message, 0, $level, $file, $line);
            //jsLog::log($message);
        //}

        jsLog::error($level, $message, $file, $line);
        $_level = "";
        switch($level) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                $_level = 'Error';
                break;
            case E_WARNING:
            case E_CORE_WARNING:
            case E_USER_WARNING:
                $_level = 'Warning';
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                $_level = 'Notice';
                break;
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $_level = 'Deprecated';
                break;

            case E_PARSE:
                $_level = 'Parse';
                break;
            case E_STRICT:
                $_level = 'Strict';
                break;

            default:
                $_level = 'Warning';
                break;
        }
        $output = "";
        if ( jsConfig::get('SHOW_ERRORS_DEVELOP') ) {
            $output .= "<h1>Fatal error</h1>";
            $output .= "<p>Uncaught Error: '" . $_level . "'</p>";
            $output .= "<p>Message: '" . $message . "'</p>";
            //$output .= "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            $output .= "<p>Thrown in '" . $file . "' on line " . $line . "</p>";
        } elseif( jsConfig::get('SHOW_ERRORS_USER') ) {
            $output .= $message;
        }else{
            $output .= "<h1>Error: </h1><p> There has been an error in the code. </p>";
        }

        jsView::render("error.php", ['message' => $output], false);

        //if (error_reporting() !== 0) {  // to keep the @ operator working
            //throw new \ErrorException($message, 0, $level, $file, $line);
            //jsLog::log($message);
        //}
        /*$output = "";
        $output .= "<h1>Fatal error</h1>";
        $output .= "<p>Uncaught exception: '" . $level . "'</p>";
        $output .= "<p>Message: '" . $message . "'</p>";
        //$output .= "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
        $output .= "<p>Thrown in '" . $file . "' on line " . $line . "</p>";
        echo $output;*/
        //throw new \ErrorException($message, 0, $level, $file, $line);

        /*$output = "";


        echo $output;*/
        //jsView::render("error.php", ['message' => $output]);
    }

    /**
     * Exception handler.
     *
     * @param Exception $exception The exception
     *
     * @return void
     * @throws \Exception
     */
    public static function jsExceptionHandler($exception) {
        // Code is 404 (not found) or 500 (general error)
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        if(get_class($exception) == "PDOException"){
            jsLog::database($exception);
        }else{
            jsLog::exception($exception);
        }

        $output = "";
        if ( jsConfig::get('SHOW_ERRORS_DEVELOP') ) {
            $output .= "<h1>Fatal error</h1>";
            $output .= "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            $output .= "<p>Message: '" . $exception->getMessage() . "'</p>";
            $output .= "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            $output .= "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        } elseif( jsConfig::get('SHOW_ERRORS_USER') ) {
            $output .= $exception->getMessage();
            /*$log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();
			$message .= "\n --------------------------------------------------------------------------";
            error_log($message);*/
        }else {
            $output .= "<h1>Error: </h1><p> There has been an error in the code. </p>";
        }

        if($code == 404){
            jsView::render("404.php", ['message' => ' صفحه مورد نظر یافت نشد ']);
        }else{
            jsView::render("error.php", ['message' => $output]);
        }
    }
}