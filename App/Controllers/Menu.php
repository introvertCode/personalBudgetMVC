<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;

/**
 * Menu controller
 * 
 */
class Menu extends Authenticated
{
    public function showAction(){
        View::renderTemplate('Menu/show.html');
    }
}