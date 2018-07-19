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
        $res = '';
        // jsRequest for get all data such as POST, GET, REQUEST and filter the value of this for xss
        //jsRequest::post('id');
        //$status = jsRequest::post('status');

        // selected in database for get all users info
        //$query = static::select(" SELECT id, `name` FROM `".DB_PREFIX."users` WHERE status = '$status' ");

        // get all users
        /*foreach($query as $row){
            $res = "userId = " . $row['id'] . " - userName = " .$row['name'];
        }*/

        // return final data to controller
        return $res;
    }

}

