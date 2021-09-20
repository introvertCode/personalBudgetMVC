<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

/**
 * Login controller
 * 
 */
class Login extends \Core\Controller
{
    /**
     * Show the login page
     * @return void
     */
    public function newAction(){
        if(Auth::getUser()){
            $this->redirect(Auth::getReturnToPage());
        } else {
            View::renderTemplate('Login/new.html');
        }
        
        
    }

    /**
     * Log in a user
     */
    public function createAction()
    {
        //$_REQUEST bierze i GET i POST.
        //echo ($_REQUEST['email'].',' .$_REQUEST['password']);
       $user = User::authenticate($_POST['email'], $_POST['password']);

       $remember_me = isset($_POST['remember_me']);

        // if($user) {
        //     header('Location: http://' . $_SERVER['HTTP_HOST'] . '/', true, 303);
        //     exit;
        // } else {
        //     View::renderTemplate('Login/new.html', ['email' => $_POST['email']]);
        // }
        if ($user) {
            
            Auth::login($user, $remember_me);
            Flash::addMessage('Login successful');
            $this->redirect(Auth::getReturnToPage());

        } else {
            Flash::addMessage('Login unsuccessful, please try again', Flash::WARNING);
            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email'],
                'remember_me' => $remember_me
            ]);
        }

    }

    /**
     * Log out a user
     * 
     * @return void
     */
    public function destroyAction(){
        Auth::logout();

        //zaczyna nową sesję
        $this->redirect('/login/show-logout-message');
        // Flash::addMessage('Logout successful');
        // $this->redirect('/');
    }

    /**
     * Show a "logged out" flash mesage and redirect to the homepage. Necessary to use the flash messages as they use the session and at the end of the logout method (destroyActoin) the session is destroyed so a new action needs to be called in order to use the session.
     * @return void
     */
    public function showLogoutMessageAction(){
        Flash::addMessage('Logout successful');
        $this->redirect('/');
    }
}