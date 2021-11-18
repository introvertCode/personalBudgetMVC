<?php
namespace App\Controllers;


use \Core\View;
use \App\Auth;
use \App\Models\Income;
use \App\Flash;

/**
 * Items controller (example)
 * Rozszerza autheticated, która rozszerza core/controller. 
 */
class IncomeManager extends Authenticated
{

    // protected function before()
    // {
    //     parent::before();
    //     $this->user = Auth::getUser();
    // }

     /**
     * Class constructor
     * 
     * @param array $data Initial property values, opcjonalny ( = [] gdy nie podajemy wartości)
     * 
     * @return void
     */
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
        $incomeCategories = Income::getIncomeCategoriesOfUser();
        View::renderTemplate('IncomeManager/show.html',['incomeCategories'=>$incomeCategories]);
    }

    static public function getIncomesCategoryOfUser(){
        return Income::getIncomeCategoriesOfUser();
    }

    /**
     * Add income
     * @return void
     */

    public function addAction(){
        $income = new Income ($_POST);
        if($income->save()){
           
            $this->redirect('/IncomeManager/show-success-message');

        } else {
            $categories = IncomeManager::getIncomesCategoryOfUser();
            Flash::addMessage('Nie udało się dodać przychodu', Flash::WARNING);
            View::renderTemplate('IncomeManager/show.html',['expenseCategories'=>$categories, 'income'=>$income]);
        //    View::renderTemplate('Signup/new.html',['user' => $user]);
        }
    }

    /**
     * Show a "logged out" flash mesage and redirect to the homepage. Necessary to use the flash messages as they use the session and at the end of the logout method (destroyActoin) the session is destroyed so a new action needs to be called in order to use the session.
     * @return void
     */
    public function showSuccessMessageAction(){
        Flash::addMessage('Dodano pomyślnie');
        $this->redirect('/IncomeManager/show');
    }

    /**
     * Update income
     */
    public function updateAction($data){
        $income = new Income ();
       
        if($income->updateIncome($data)) {
            Flash::addMessage('Zapisano zmiany');
            $this->redirect('/BalanceManager/add');
        } else {

        }

    }

    public function deleteAction($id){
        $income = new Income ();

        if($income->deleteIncome($id)) {
            Flash::addMessage('Usunięto pomyślnie');
            $this->redirect('/BalanceManager/add');
        }

    }

    static public function addIncomeCategoryAction($data){
        $income = new Income();
        return $income->addIncomeCategory($data);
        
    }

    static public function deleteIncomeCategoryAction($data){
        
        return Income::deleteIncomeCategory($data);
        
    }

    static public function changeIncomeCategoryNameAction($data){
       return  Income::changeIncomeCategoryName($data);
    }
}