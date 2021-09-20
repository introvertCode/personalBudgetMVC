<?php

namespace App\Controllers;
use \Core\View;
use \App\Auth;

class Home extends \Core\Controller
{
    protected function before(){
        //echo "(before)";
        //gdy funkcja zwróci false to nie wywoła się właściwa metoda (warunek w funkcji __call)
        // return false;
    }

    protected function after(){
        //echo "(after)";
    }

    //metoda wywołuje się gdy nic nie wpiszemy ponieważ to jest jedna z routes, która ma kontroler home i akcje index.
    public function indexAction(){
        //echo 'Hello from the index action in th e Home controller!';
        // View::render('Home/index.php', ['name'=>'Dave', 'colours'=>['red', 'green', 'blue']]);
        //View::renderTemplate('Home/index.html',['name'=>'Dave', 'colours'=>['red', 'green', 'blue']]);
        
        View::renderTemplate('Home/index.html');
    }
}