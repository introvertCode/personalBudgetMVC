<?php

namespace App\Controllers\Admin;

class Users extends \Core\Controller{
    protected function before(){

        //for example check if admin user is logged in 
    }

    public function indexAction(){
        echo 'User admin index';
    }
}