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
            Flash::addMessage('Changes saved');

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
        if (IncomeManager::addIncomeCategory($_POST)){
            Flash::addMessage('Adding successful');
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
        IncomeManager::deleteIncomeCategory($_POST);
        $this->redirect('/profile/show');
        Flash::addMessage('Usunięto pomyślnie');
    }

    /**
     * Add expense category
     * @return void
     */
    public function addExpenseCategoryAction(){
        if (ExpenseManager::addExpenseCategory($_POST)){
            Flash::addMessage('Adding successful');
            $this->redirect('/profile/show');
           
        } else {
            Flash::addMessage('Taka kategoria już istnieje','warning');
            $this->redirect('/profile/show');
            echo "już istnieje taka kategoria";
            
        }
    }

     /**
     * Delete expense category
     * @return void
     */
    public function deleteExpenseCategoryAction(){
        ExpenseManager::deleteExpenseCategory($_POST);
        $this->redirect('/profile/show');
        Flash::addMessage('Usunięto pomyślnie');
    }

    /**
     * Delete expense category
     * @return void
     */
    public function deletePaymentMethodAction(){
        ExpenseManager::deletePaymentMethod($_POST);
        $this->redirect('/profile/show');
        Flash::addMessage('Usunięto pomyślnie');
    }

    public function addPaymentMethodAction(){
        if (ExpenseManager::addPaymentMethod($_POST)){
            Flash::addMessage('Adding successful');
            $this->redirect('/profile/show');
           
        } else {
            Flash::addMessage('Taka kategoria już istnieje','warning');
            $this->redirect('/profile/show');
            echo "już istnieje taka kategoria";
            
        }
    }

}