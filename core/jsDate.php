<?php

namespace core;



if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}


/**
 * jsDate
 *
 * PHP version 7.1
 */
class jsDate {

    /**
     * get
     * convert miladi to shamsi
     *
     * @param string $format
     * @param string $timestamp
     * @return void
     * @throws \Exception
     */
    public static function get($format = "Y-m-d H:i:s", $timestamp = "") {
        $date = new \jDateTime(false, true, jsConfig::get('TIMEZONE'));
        if (empty($timestamp)) {
            return $date->date($format);
        }
        return $date->date($format, $timestamp);
    }
}
