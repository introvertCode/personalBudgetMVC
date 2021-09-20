<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;

class Signup extends \Core\Controller
{
    /**
     * Show the signup page
     * 
     * @return void
     */
    public function newAction(){
        // View::renderTemplate('Signup/new.html');
        if(Auth::getUser()){
            $this->redirect(Auth::getReturnToPage());
        } else {
            View::renderTemplate('Signup/new.html');
        }
    }



    /**
     * Sign up a new user
     * 
     * @return void
     */
    public function createAction(){
        $user = new User ($_POST);
        if($user->save()){
            
            $user->sendActivationEmail();
            $user->addDefaultIncomeCategories();
            $user->addDefaultExpenseCategories();
            //przekierowuje do adresu /signup/success, co wpisuje się w schemat model: signup, action: success i uruchamia metodę successAction, która przekierowuje na stronę success.html
            $this->redirect('/signup/success');

           
        } else {
           View::renderTemplate('Signup/new.html',['user' => $user]);
        }
    }

    /**
     * Show the signup success page
     * 
     * @return void
     */
    public function successAction(){
        View::renderTemplate('Signup/success.html');
    }

    /**
     * Activate a new account
     * @return void
     */
    public function activateAction(){
        User::activate($this->route_params['token']);
        
        $this->redirect('/Signup/activated');
    }

    /**
     * Show the activation success page
     * @return void
     */
    public function activatedAction(){
        View::renderTemplate('Signup/activated.html'); 
    }

}
