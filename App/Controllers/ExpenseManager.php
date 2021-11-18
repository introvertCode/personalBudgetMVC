<?php
namespace App\Controllers;


use \Core\View;
use \App\Models\Expense;
use \App\Flash;

/**
 * Items controller (example)
 * Rozszerza autheticated, która rozszerza core/controller. 
 */
class ExpenseManager extends Authenticated
{

    // protected function before()
    // {
    //     parent::before();
    //     $this->user = Auth::getUser();
    // }
    public function __construct($data = []){
        foreach ($data as $key => $value) {
            
            //$this użyte do dynamicznego ustawiania atrybutu obiektu, w tym wypadku klucza jako właściwości obiektu i przypisanie do niego wartości.
            $this->$key=$value;
        };
    }

    /**
     * Items index
     * @return void
     */
    public function showAction(){
        Expense::getPaymentMethodsOfUser();
        
        $categories = ExpenseManager::getExpenseCategoriesOfUser();
        $paymentMethods = Expense::getPaymentMethodsOfUser();
        View::renderTemplate('ExpenseManager/show.html',['expenseCategories'=>$categories, 'paymentMethods'=>$paymentMethods]);
    }

    static public function getExpenseCategoriesOfUser(){
        return Expense::getExpenseCategoriesOfUser();
    }

     /**
     * Add income
     * @return void
     */
    public function addAction(){
        $expense = new Expense ($_POST);
       
        if($expense->save()){
           
            $this->redirect('/ExpenseManager/show-success-message');

        } else {
            $categories = ExpenseManager::getExpenseCategoriesOfUser();
            $paymentMethods = Expense::getPaymentMethodsOfUser();
            Flash::addMessage('Nie udało się dodać przychodu', Flash::WARNING);
            View::renderTemplate('ExpenseManager/show.html',['expenseCategories'=>$categories, 'paymentMethods'=>$paymentMethods, 'expense'=>$expense]);
        }
    }

    /**
     * Show a "logged out" flash mesage and redirect to the homepage. Necessary to use the flash messages as they use the session and at the end of the logout method (destroyActoin) the session is destroyed so a new action needs to be called in order to use the session.
     * @return void
     */
    public function showSuccessMessageAction(){
        Flash::addMessage('Dodano pomyślnie');
        $this->redirect('/ExpenseManager/show');
    }

    /**
     * Update income
     */
    public function updateAction($data){
        $expense = new Expense ();
       
        if($expense->updateExpense($data)) {
            Flash::addMessage('Zapisano zmiany');
            $this->redirect('/BalanceManager/add');
        } else {
            Flash::addMessage('Nie udało się dodać przychodu', Flash::WARNING);
            $this->redirect('/BalanceManager/add');
        }

    }

    public function deleteAction($id){
        $expense = new Expense ();

        if($expense->deleteExpense($id)) {
            Flash::addMessage('Usunięto pomyślnie');
            $this->redirect('/BalanceManager/add');
        }

    }

    static public function addExpenseCategory($data){
        $expense = new Expense();
        return $expense->addExpenseCategory($data);
        
    }

    static public function addPaymentMethod($data){
        $expense = new Expense();
        return $expense->addPaymentMethod($data);
        
    }

    static public function deleteExpenseCategory($data){
        
        return Expense::deleteExpenseCategory($data);
        
    }

    static public function getPaymentMethodsOfUser(){
        return Expense::getPaymentMethodsOfUser();

    }

    static public function deletePaymentMethod($data){
        
        return Expense::deletePaymentMethod($data);
        
    }

    static public function setLimit($data){
        
        return Expense::setLimit($data);
        
    }

    static public function showLimitAction(){
        $limit = Expense::getLimit($_POST);
        echo  json_encode($limit);
        
        // return Expense::getLimit($_POST);
        
    }

    static public function changePaymentMethodName($data){
        $expense = new Expense();
        return $expense->changePaymentMethodName($data);

    }
}