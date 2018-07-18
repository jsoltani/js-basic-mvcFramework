<?php

namespace app\models;

if( !defined( 'JS') ){
    die(' Access Denied !!! ');
}

use core\jsModel;
use core\jsRequest;

class test extends jsModel {

    /**
     * get all information of users
     */
    public static function getUsers(){

        // jsRequest for get all data such as POST, GET, REQUEST
        //jsRequest::post('id');
        //$status = jsRequest::post('status');

        //$query = static::select(" SELECT id, `name` FROM `".DB_PREFIX."users` WHERE status = '$status' ");
        //$res = '';
        /*foreach($query as $row){
            $res = "userId = " . $row['id'] . " - userName = " .$row['name'];
        }*/
        // return $res;
    }

}

