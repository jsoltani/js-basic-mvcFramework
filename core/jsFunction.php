<?php

namespace core;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

/**
 * jsFunction
 *
 * PHP version 7.1
 */
class jsFunction {

    /**
     * jsResponse
     * for set output data
     *
     * @param array $data
     * @param int $status
     *
     * @return json|void
     */
    public static function jsResponse($data, $status = 200){
        $_status = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Time-out',
            505 => 'HTTP Version not supported'
        );
        $res_Status = ($status[$_status]) ? $status[$_status] : $status[500];

        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1');
        header("Status: " . $status);
        header($protocol . ' ' . $status . ' ' . $res_Status);
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * getClientIP
     * for get user ip
     *
     * @return string
     */
    public static function getClientIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * redirect
     * redirect to url
     * @param $url
     */
    public static function redirect($url) {
        if (!headers_sent()){
            header('Location: '.$url); exit;
        }
        else{
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.$url.'";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
            echo '</noscript>'; exit;
        }
    }

    /**
     * jsCallback
     * redirect to url
     * @param $url
     * @param $data
     * @throws \Exception
     */
    public static function jsCallback($url = '', $data = []) {

        $rsa = new jsRsaCrypt();
        $url = $url . '?data=' .$rsa->encrypt(json_encode($data));

        if (!headers_sent()){
            header('Location: '.$url); exit;
        }
        else{
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.$url.'";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
            echo '</noscript>'; exit;
        }
    }

    /**
     * setIcheckAccess
     *
     * @param array $accessList
     * @param $access
     * @return string
     * @throws \Exception
     */
    public static function setIcheckAccess($accessList = [], $access){

        if(!is_array($accessList)){
            throw new \Exception(' error ');
        }

        $res = "";
        foreach ($accessList as $pageLink => $pageName){
            if(!empty($access)){
                $view   = ( $access[$pageLink]['view'] === 'true' ) ? 'checked' : '';
                $add    = ( $access[$pageLink]['add'] === 'true' ) ? 'checked' : '';
                $edit   = ( $access[$pageLink]['edit'] === 'true' ) ? 'checked' : '';
                $delete = ( $access[$pageLink]['delete'] === 'true' ) ? 'checked' : '';
            }else{
                $view   = 'checked';
                $add    = 'checked';
                $edit   = 'checked';
                $delete = 'checked';
            }

            $res .= '
                <div class="form-group m-b-20 dir-rtl accessBorder">
                    <div class="col-md-3 pull-right"> '.$pageName.' </div>
                    <div class="col-md-9 pull-right">
                        <div class="col-md-3 m-b-10 pull-right">
                            <input type="checkbox" id="'.$pageLink.'_view" '.$view.' class="check" data-checkbox="icheckbox_line-blue" data-label="'.jsLanguage::get('view').'">
                        </div>
                        
                        <div class="col-md-3 m-b-10 pull-right">
                            <input type="checkbox" id="'.$pageLink.'_add" '.$add.' class="check" data-checkbox="icheckbox_line-green" data-label="'.jsLanguage::get('add').'">
                        </div>
                        
                        <div class="col-md-3 m-b-10 pull-right">
                            <input type="checkbox" id="'.$pageLink.'_edit" '.$edit.' class="check" data-checkbox="icheckbox_line-yellow" data-label="'.jsLanguage::get('edit').'">
                        </div>
                        
                        <div class="col-md-3 m-b-10 pull-right">
                            <input type="checkbox" id="'.$pageLink.'_delete" '.$delete.' class="check" data-checkbox="icheckbox_line-red" data-label="'.jsLanguage::get('delete').'">
                        </div>
                    </div>
                </div>	
            ';
        }

        return $res;
    }
}
