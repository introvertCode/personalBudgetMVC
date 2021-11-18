<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Income;
use \App\Models\Expense;


/**
 * Profile controller
 * 
 */
class Profile extends Authenticated
{
    /**
     * Before filter - called before each action method
     * @return void
     */
    protected function before()
    {
        parent::before();
        $this->user = Auth::getUser();
        $this->incomeCategories = Income::getIncomeCategoriesOfUser();
        $this->expenseCategories = Expense::getExpenseCategoriesOfUser();
        $this->paymentMethods = Expense::getPaymentMethodsOfUser();
    }
    
    
    
    /**
     * Show th eprofile
     * @return void
     * 
     */
    public function showAction(){
        View::renderTemplate('Profile/show.html', [
            // 'user'=> Auth::getUser()
            'user' => $this->user,
            'incomeCategories' => $this->incomeCategories,
            'expenseCategories' => $this->expenseCategories,
            'paymentMethods' => $this->paymentMethods
        ]);
    }

    /**
     * Show the form for editing the profile
     * @return void
     */
    public function editAction(){
        View::renderTemplate('Profile/edit.html', [
            'user' => $this->user
        ]);
    }

    /**
     * Update the profile
     * @return void
     */
    public function updateAction(){
        //$user = Auth::getUser();

        if($this->user->updateProfile($_POST)) {
            Flash::addMessage('Zapisano zmiany');
            $this->redirect('/profile/show');
        } else {
            View::renderTemplate('Profile/edit.html', [
                'user' => $this->user
            ]);
        }
    }
    /**
     * Add income category
     * @return void
     */
    public function addIncomeCategoryAction(){
        if (IncomeManager::addIncomeCategoryAction($_POST)){
            Flash::addMessage('Dodano pomyślnie');
            $this->redirect('/profile/show');
           
        } else {
            Flash::addMessage('Taka kategoria już istnieje','warning');
            $this->redirect('/profile/show');
            echo "już istnieje taka kategoria";
            
        }
    }

    /**
     * Delete income category
     * @return void
     */
    public function deleteIncomeCategoryAction(){
        if(IncomeManager::deleteIncomeCategoryAction($_POST)){
            Flash::addMessage('Usunięto pomyślnie');
            $this->redirect('/profile/show');
           
        }
       
    }

    /**
     * Add expense category
     * @return void
     */
    public function addExpenseCategoryAction(){
        if (ExpenseManager::addExpenseCategory($_POST)){
            Flash::addMessage('Dodano pomyślnie');
            $this->redirect('/profile/show');
           
        } else {
            Flash::addMessage('Taka kategoria już istnieje','warning');
            $this->redirect('/profile/show');
            // echo "już istnieje taka kategoria";
            
        }
    }

     /**
     * Delete expense category
     * @return void
     */
    public function deleteExpenseCategoryAction(){
        ExpenseManager::deleteExpenseCategory($_POST);
        Flash::addMessage('Usunięto pomyślnie');
        $this->redirect('/profile/show');
        
    }

    /**
     * Delete expense category
     * @return void
     */
    public function deletePaymentMethodAction(){
        ExpenseManager::deletePaymentMethod($_POST);
        Flash::addMessage('Usunięto pomyślnie');
        $this->redirect('/profile/show');
       
    }

    public function addPaymentMethodAction(){
        if (ExpenseManager::addPaymentMethod($_POST)){
            Flash::addMessage('Dodano pomyślnie');
            $this->redirect('/profile/show');
           
        } else {
            Flash::addMessage('Taka kategoria już istnieje','warning');
            $this->redirect('/profile/show');
            // echo "już istnieje taka kategoria";
            
        }
    }

    public function setExpenseLimitAction(){
        // $this->redirect('/profile/show');
       if (ExpenseManager::setLimit($_POST)){
            // $this->redirect('/profile/show');
            return true;
       } else {
           return false;
       }
    //    Flash::addMessage('Zmieniono pomyślnie');
    }

    /**
     * Change income category name
     * @return void
     */
    public function changeIncomeCategoryNameAction(){
        if (IncomeManager::changeIncomeCategoryNameAction($_POST)){
            Flash::addMessage('Zmieniono pomyślnie');
            $this->redirect('/profile/show');
        } else {
            Flash::addMessage('Taka kategoria już istnieje','warning');
            $this->redirect('/profile/show');
        }
       
    }
   
    /**
     * Change payment method name
     * @return void
     */
    public function changePaymentMethodNameAction(){
        if (ExpenseManager::changePaymentMethodName($_POST)){
            Flash::addMessage('Zmieniono pomyślnie');
            $this->redirect('/profile/show');
        } else {
            Flash::addMessage('Taka kategoria już istnieje','warning');
            $this->redirect('/profile/show');
        }
       
    }
}   