<?php

namespace core;

use voku\helper\AntiXSS;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

/**
 * jsRequest
 *
 * PHP version 7.1
 */
class jsRequest {

    /**
     * isMethod
     * for check http method
     *
     * @param string $method
     * @return bool
     */
    public static function isMethod($method = "GET") {
        if(empty($method))
            return false;
        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            return false;
        }
        return true;
    }

    /**
     * isNumber
     * check value for a valid number
     *
     * @param string $number
     * @return bool
     */
    public static function isNumber($number = "") {
        if(empty($number))
            return false;
        if (!ctype_digit($number) || !is_numeric($number)){
            return false;
        }
        return true;
    }

    /**
     * isEmail
     * check value for a valid email
     *
     * @param string $email
     * @return bool
     */
    public static function isEmail($email = "") {
        if(empty($email))
            return false;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    /**
     * isUrl
     * check value for a valid url
     *
     * @param string $url
     * @return bool
     */
    public static function isUrl($url = "") {
        if(empty($url))
            return false;
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        return true;
    }

    /**
     * isMobile
     * for check a valid mobile number
     *
     * @param string $mobile
     * @return bool
     */
    public static function isMobile($mobile = "") {
        if(empty($mobile))
            return false;
        # check if input has 10 digits that all of them are not equal
        if(!preg_match("/^09[0-9]{9}$/", $mobile)) {
            return false;
        }

        return true;
    }

    /**
     * isNationalCode
     * for check a valid national code
     *
     * @param string $nationalCode
     * @return bool
     */
    public static function isNationalCode($nationalCode = "") {
        if(empty($nationalCode))
            return false;
        if (!preg_match("/^\d{10}$/", $nationalCode)) {
            return false;
        }

        $check = (int) $nationalCode[9];
        $sum = array_sum(array_map(function ($x) use ($nationalCode) {
                return ((int) $nationalCode[$x]) * (10 - $x);
            }, range(0, 8))) % 11;

        return ($sum < 2 && $check == $sum) || ($sum >= 2 && $check + $sum == 11);
    }

    /**
     * request
     * for check request variable
     *
     * @param string $request
     * @return string
     */
    public static function request($request = "") {
        if(empty($request))
            return "";

        $requestVal = isset($_REQUEST[$request]) ? $_REQUEST[$request] : '';
        $requestVal = trim($requestVal);
        if(empty($requestVal))
            return "";
        $antiXss = new AntiXSS();
        $requestVal = $antiXss->xss_clean($requestVal);
        return $requestVal;
    }

    /**
     * post
     * for check post variable
     *
     * @param string $post
     * @return string
     */
    public static function post($post = "") {
        if(empty($post))
            return "";

        $postVal = isset($_POST[$post]) ? $_POST[$post] : '';
        $postVal = trim($postVal);
        if(empty($postVal))
            return "";
        $antiXss = new AntiXSS();
        $postVal = $antiXss->xss_clean($postVal);
        return $postVal;
    }

    /**
     * get
     * for check get variable
     *
     * @param string $get
     * @return string
     */
    public static function get($get = "") {
        if(empty($get))
            return "";

        $getVal = isset($_GET[$get]) ? $_GET[$get] : '';
        $getVal = trim($getVal);
        if(empty($getVal))
            return "";
        $antiXss = new AntiXSS();
        $getVal = jsSecurity::jsUrlClean($getVal);
        $getVal = $antiXss->xss_clean($getVal);
        return $getVal;
    }

    /**
     * check
     * for check variable
     *
     * @param string $parameter
     * @return string
     */
    public static function check($parameter = "") {
        if(empty($parameter))
            return "";
        $antiXss = new AntiXSS();
        $getVal = jsSecurity::jsUrlClean($parameter);
        $getVal = $antiXss->xss_clean($getVal);
        return $getVal;
    }
}
