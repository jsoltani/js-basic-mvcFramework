<?php

namespace app\controllers;

if( !defined( 'JS') ){
    die(' Access Denied !!! ');
}

use core\jsController as controller;
use core\jsView;

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
}