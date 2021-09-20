<?php
namespace App\Controllers;

/**
 * Authenticated base controller
 * Klasa ta będzie rozszerzeniem dla wszystkich stron, które mają być dostępne po zalogowaniu. Funkcja before() sprawdza czy użytkownik jest zalogowany
 */
abstract class Authenticated extends \Core\Controller
{
    /**
     * Require the user to be authenticated before gibing access to all methods in the controller
     * @return void
     */
    protected function before(){
        $this->requireLogin();
    }
}