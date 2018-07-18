<?php

namespace app\controllers;

if( !defined( 'JS') ){
    die(' Access Denied !!! ');
}

use core\jsController as controller;
use core\jsView;
use \app\models\test as testModel;

/**
 * test controller
 *
 * PHP version 7.1
 */
class test extends controller {

    /**
     * before
     */
    protected function before() {

    }

    /**
     * Show the index page
     *
     * @return void
     * @throws \Exception
     */
    public function indexAction()
    {
        jsView::render('test/index.php', [
            'title' => 'Test Page',
            'content' => 'Test Page Content'
        ]);

    }

    /**
     * get all information of users
     */
    public function getAllUsersAction() {
        jsView::render('test/get-all-users.php',[
            'title' => 'get all users',
            'userInfo' => testModel::getUsers()
        ]);
    }
}