<?php

namespace core;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

@header('Content-type: text/html; charset=utf-8');
/**
 * jsLog
 *
 * PHP version 7.1
 */
class jsLog {

    /**
     * database
     * set error logs in error log file
     *
     * @param $exception
     * @throws \Exception
     */
    public static function database($exception) {
        if(empty($exception)){
            throw new \Exception(" مقدار ورودی این تابع نمی تواند خالی باشد ");
        }
        //@header('Content-type: text/html; charset=utf-8');
        mb_internal_encoding('UTF-8');
        $logfile = jsConfig::get('APP_DATABASE_PATH') . jsConfig::get('DB_DATABASE') . '_' . date('Y_m_d') . '.txt';
        if(!file_exists($logfile)){
            if(!file_exists(jsConfig::get('APP_DATABASE_PATH'))){
                mkdir(jsConfig::get('APP_DATABASE_PATH'), 0777);
            }
        }
        $new = file_exists($logfile) ? false : true;
        if($handle = fopen($logfile, 'a')) {
            $log_headers    = getallheaders();
            $log_requestUri = (isset($_SERVER['HTTPS']) ? "https" : "http") . htmlentities("://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            $log_method     = $_SERVER['REQUEST_METHOD'];

            $content = "[".date('Y-m-d H:i:s')."] [Client Ip: ".jsFunction::getClientIP()."] \n";
            $content .= "Headers : \n";
            foreach ($log_headers as $key => $val){
                if(is_array($val)){
                    $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                }else{
                    $content .= '# ' . $key . ' : ' . $val . "\n";
                }
            }
            $content .= "Request Uri : " . $log_requestUri . "\n";
            $content .= "Method : " . $log_method . "\n";
            if(isset($_GET)){
                $content .= "GET : \n";
                foreach ($_GET as $key => $val){
                    if(is_array($val)){
                        $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                    }else{
                        $content .= '# ' . $key . ' : ' . $val . "\n";
                    }
                }
            }
            if(isset($_POST)){
                $content .= "POST : \n";
                foreach ($_POST as $key => $val){
                    if(is_array($val)){
                        $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                    }else{
                        $content .= '# ' . $key . ' : ' . $val . "\n";
                    }
                }
            }
            $content .= " ********************************* Error ********************************* \n";
            $content .= "Uncaught exception: '" . get_class($exception) ."'\n";
            $content .= "Code : [" . $exception->getCode() . "] \n";
            $content .= "Message : " . $exception->getMessage() . "\n";
            $content .= "Stack trace : \n" . $exception->getTraceAsString()  . "\n";
            $content .= "Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "\n";
            $content .= "-------------------------------------------------------------------------------------------------------------------------------------\n";
            fwrite($handle, pack("CCC",0xef,0xbb,0xbf));
            fwrite($handle, $content);
            fclose($handle);
            if($new) { chmod($logfile, 0755); }
        }
        else {
            throw new \Exception("در باز کردن فایل خطا مشکلی بوجود آمده .");
        }
    }

    /**
     * exception
     * set exception logs in error log file
     *
     * @param $exception
     * @throws \Exception
     */
    public static function exception($exception) {
        if(empty($exception)){
            throw new \Exception(" مقدار ورودی این تابع نمی تواند خالی باشد ");
        }

        mb_internal_encoding('UTF-8');
        $logfile = jsConfig::get('APP_EXCEPTION_PATH') . 'Exception_' . date('Y_m_d') . '.txt';
        if(!file_exists($logfile)){
            if(!file_exists(jsConfig::get('APP_EXCEPTION_PATH'))){
                mkdir(jsConfig::get('APP_EXCEPTION_PATH'), 0777);
            }
        }
        $new = file_exists($logfile) ? false : true;
        if($handle = fopen($logfile, 'a')) {
            $log_headers    = getallheaders();
            $log_requestUri = (isset($_SERVER['HTTPS']) ? "https" : "http") . htmlentities("://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            $log_method     = $_SERVER['REQUEST_METHOD'];

            $content = "[".date('Y-m-d H:i:s')."] [Client Ip: ".jsFunction::getClientIP()."]\n";
            $content .= "Headers : \n";
            foreach ($log_headers as $key => $val){
                if(is_array($val)){
                    $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                }else{
                    $content .= '# ' . $key . ' : ' . $val . "\n";
                }
            }
            $content .= "Request Uri : " . $log_requestUri . "\n";
            $content .= "Method : " . $log_method . "\n";
            if(isset($_GET)){
                $content .= "GET : \n";
                foreach ($_GET as $key => $val){
                    if(is_array($val)){
                        $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                    }else{
                        $content .= '# ' . $key . ' : ' . $val . "\n";
                    }
                }
            }
            if(isset($_POST)){
                $content .= "POST : \n";
                foreach ($_POST as $key => $val){
                    if(is_array($val)){
                        $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                    }else{
                        $content .= '# ' . $key . ' : ' . $val . "\n";
                    }
                }
            }
            $content .= " ********************************* Error ********************************* \n";
            $content .= "Uncaught exception: '" . get_class($exception) ."'\n";
            $content .= "Code : [" . $exception->getCode() . "] \n";
            $content .= "Message : " . $exception->getMessage() . "\n";
            $content .= "Stack trace : \n" . $exception->getTraceAsString()  . "\n";
            $content .= "Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "\n";
            $content .= "-------------------------------------------------------------------------------------------------------------------------------------\n";
            fwrite($handle, pack("CCC",0xef,0xbb,0xbf));
            fwrite($handle, $content);
            fclose($handle);
            if($new) { chmod($logfile, 0755); }
        }
        else {
            throw new \Exception("در باز کردن فایل خطا مشکلی بوجود آمده .");
        }
    }

    /**
     * error
     * set error logs in error log file
     *
     * @param $level
     * @param $message
     * @param $file
     * @param $line
     * @throws \Exception
     */
    public static function error($level, $message, $file, $line) {
        if( empty($level) || empty($message) || empty($file) || empty($line) ){
            throw new \Exception(" مقدار ورودی این تابع نمی تواند خالی باشد ");
        }
        //@header('Content-type: text/html; charset=utf-8');
        mb_internal_encoding('UTF-8');
        $logfile = jsConfig::get('APP_ERROR_PATH') . 'Errors_' . date('Y_m_d') . '.txt';
        if(!file_exists($logfile)){
            if(!file_exists(jsConfig::get('APP_ERROR_PATH'))){
                mkdir(jsConfig::get('APP_ERROR_PATH'), 0777);
            }
        }
        $new = file_exists($logfile) ? false : true;
        if($handle = fopen($logfile, 'a')) {
            $log_headers    = getallheaders();
            $log_requestUri = (isset($_SERVER['HTTPS']) ? "https" : "http") . htmlentities("://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            $log_method     = $_SERVER['REQUEST_METHOD'];

            $content = "[".date('Y-m-d H:i:s')."] [Client Ip: ".jsFunction::getClientIP()."]\n";
            $content .= "Headers : \n";
            foreach ($log_headers as $key => $val){
                if(is_array($val)){
                    $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                }else{
                    $content .= '# ' . $key . ' : ' . $val . "\n";
                }
            }
            $content .= "Request Uri : " . $log_requestUri . "\n";
            $content .= "Method : " . $log_method . "\n";
            if(isset($_GET)){
                $content .= "GET : \n";
                foreach ($_GET as $key => $val){
                    if(is_array($val)){
                        $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                    }else{
                        $content .= '# ' . $key . ' : ' . $val . "\n";
                    }
                }
            }
            if(isset($_POST)){
                $content .= "POST : \n";
                foreach ($_POST as $key => $val){
                    if(is_array($val)){
                        $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                    }else{
                        $content .= '# ' . $key . ' : ' . $val . "\n";
                    }
                }
            }

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

            $content .= " ********************************* Error ********************************* \n";
            $content .= "Uncaught Error: '" . $_level ."'\n";
            $content .= "Code : [" . $level . "] \n";
            $content .= "Message : " . $message . "\n";
            $content .= "Thrown in '" . $file . "' on line " . $line . "\n";
            $content .= "-------------------------------------------------------------------------------------------------------------------------------------\n";
            fwrite($handle, pack("CCC",0xef,0xbb,0xbf));
            fwrite($handle, $content);
            fclose($handle);
            if($new) { chmod($logfile, 0755); }
        }
        else {
            throw new \Exception("در باز کردن فایل خطا مشکلی بوجود آمده .");
        }
    }

    /**
     * log
     * set logs in log file
     * @param string $message
     * @throws \Exception
     */
    public static function log($message = "") {
        if(empty($message)){
            throw new \Exception(" مقدار ورودی این تابع نمی تواند خالی باشد ");
        }
        //@header('Content-type: text/html; charset=utf-8');
        mb_internal_encoding('UTF-8');
        $logfile = jsConfig::get('APP_LOG_PATH') . 'Log_' . date('Y_m_d') . '.txt';
        if(!file_exists($logfile)){
            if(!file_exists(jsConfig::get('APP_LOG_PATH'))){
                mkdir(jsConfig::get('APP_LOG_PATH'), 0777);
            }
        }
        $new = file_exists($logfile) ? false : true;
        if($handle = fopen($logfile, 'a')) {

            $log_headers    = getallheaders();
            $log_requestUri = (isset($_SERVER['HTTPS']) ? "https" : "http") . htmlentities("://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            $log_method     = $_SERVER['REQUEST_METHOD'];

            $content = "[".date('Y-m-d H:i:s')."] [Client Ip: ".jsFunction::getClientIP()."]\n";
            $content .= "Headers : \n";
            foreach ($log_headers as $key => $val){
                if(is_array($val)){
                    $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                }else{
                    $content .= '# ' . $key . ' : ' . $val . "\n";
                }
            }
            $content .= "Request Uri : " . $log_requestUri . "\n";
            $content .= "Method : " . $log_method . "\n";
            if(isset($_GET)){
                $content .= "GET : \n";
                foreach ($_GET as $key => $val){
                    if(is_array($val)){
                        $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                    }else{
                        $content .= '# ' . $key . ' : ' . $val . "\n";
                    }
                }
            }
            if(isset($_POST)){
                $content .= "POST : \n";
                foreach ($_POST as $key => $val){
                    if(is_array($val)){
                        $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                    }else{
                        $content .= '# ' . $key . ' : ' . $val . "\n";
                    }
                }
            }
            $content .= " ********************************* Log ********************************* \n";
            $content .= "User: " . jsEncryption::decrypt(jsSession::get('username')) . "'\n";
            $content .= "Message: '" . $message ."'\n";
            $content .= "-------------------------------------------------------------------------------------------------------------------------------------\n";
            fwrite($handle, pack("CCC",0xef,0xbb,0xbf));
            fwrite($handle, $content);
            fclose($handle);
            if($new) { chmod($logfile, 0755); }
        }
        else {
            throw new \Exception("در باز کردن فایل لاگ خطا مشکلی بوجود آمده .");
        }
    }

    /**
     * pdoLog
     * set logs in pdoLog file
     * @param string $message
     * @throws \Exception
     */
    public static function pdoLog($message = "") {
        if(empty($message)){
            throw new \Exception(" مقدار ورودی این تابع نمی تواند خالی باشد ");
        }
        //@header('Content-type: text/html; charset=utf-8');
        mb_internal_encoding('UTF-8');
        $logfile = jsConfig::get('APP_DATABASE_PATH') . jsConfig::get('DB_DATABASE') . '_pdo_' . date('Y_m_d') . '.txt';
        if(!file_exists($logfile)){
            if(!file_exists(jsConfig::get('APP_DATABASE_PATH'))){
                mkdir(jsConfig::get('APP_DATABASE_PATH'), 0777);
            }
        }
        $new = file_exists($logfile) ? false : true;
        if($handle = fopen($logfile, 'a')) {

            $log_headers    = getallheaders();
            $log_requestUri = (isset($_SERVER['HTTPS']) ? "https" : "http") . htmlentities("://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            $log_method     = $_SERVER['REQUEST_METHOD'];

            $content = "[".date('Y-m-d H:i:s')."] [Client Ip: ".jsFunction::getClientIP()."]\n";
            $content .= "Headers : \n";
            foreach ($log_headers as $key => $val){
                if(is_array($val)){
                    $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                }else{
                    $content .= '# ' . $key . ' : ' . $val . "\n";
                }
            }
            $content .= "Request Uri : " . $log_requestUri . "\n";
            $content .= "Method : " . $log_method . "\n";
            if(isset($_GET)){
                $content .= "GET : \n";
                foreach ($_GET as $key => $val){
                    if(is_array($val)){
                        $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                    }else{
                        $content .= '# ' . $key . ' : ' . $val . "\n";
                    }
                }
            }
            if(isset($_POST)){
                $content .= "POST : \n";
                foreach ($_POST as $key => $val){
                    if(is_array($val)){
                        $content .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                    }else{
                        $content .= '# ' . $key . ' : ' . $val . "\n";
                    }
                }
            }

            $_message = "";
            if(is_array($message) and !empty($message)){
                $_message .= "Message : \n";
                foreach ($message as $key => $val){
                    $_message .= '# ' . $key . ' : ' . json_encode($val) . "\n";
                }
            }

            $content .= " ********************************* Log ********************************* \n";
            $content .= "User: " . jsEncryption::decrypt(jsSession::get('username')) . "'\n";
            $content .= $_message;
            $content .= "-------------------------------------------------------------------------------------------------------------------------------------\n";
            fwrite($handle, pack("CCC",0xef,0xbb,0xbf));
            fwrite($handle, $content);
            fclose($handle);
            if($new) { chmod($logfile, 0755); }
        }
        else {
            throw new \Exception("در باز کردن فایل لاگ خطا مشکلی بوجود آمده .");
        }
    }

}
