<?php

namespace app\controllers;

if( !defined( 'JS') ){
    die(' Access Denied !!! ');
}

use core\jsController as controller;
use core\jsView;

/**
 * Home controller
 *
 * PHP version 7.1
 */
class home extends controller {

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
        jsView::render('home/index.php', [
            'title' => 'Home Page',
            'content' => 'Home Page Content'
        ]);

    }
}