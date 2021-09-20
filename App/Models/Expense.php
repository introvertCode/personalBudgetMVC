<?php

namespace App\Models;

use PDO;
use \Core\View;
use \App\Auth;

/**
 * Income model
 * 
 * klasa rozszerza klasę Model, wiec może użyć funkcji protected static getDB()
 */

class Expense extends \Core\Model
{

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
     * Save the user model with the current property values
     * 
     * @return void
     */
    public function save(){

        $this->validate();

        if (empty($this->errors)) {
            
            //prepared statements - placeholdery :name, :email itd.
            

            $this->user = Auth::getUser();
            $loggedUserId = $this->user->id;
            $expense_category_assigned_to_user_id = Expense::getCategoryId($loggedUserId, $this->category);
            $payment_method_assigned_to_user_id = Expense::getPaymentMethodId($loggedUserId, $this->paymentMethod);

            $sql = 'INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment) VALUES (:user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            //name email i password to zmienne tworzone podczas tworzenia obiektu przez konstruktor $this->key=$value. Przypisujemy placeholderom ich wartość.
            $stmt->bindValue(':user_id', $this->user->id, PDO::PARAM_INT);
            $stmt->bindValue(':expense_category_assigned_to_user_id', $expense_category_assigned_to_user_id, PDO::PARAM_INT);
            $stmt->bindValue(':payment_method_assigned_to_user_id', $payment_method_assigned_to_user_id, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_expense', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':expense_comment',$this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }
       
        return false;
    }

    /**
     * Validate current property values, adding valiation error mesages to the errors array property
     * 
     * @return void 
     */
    public function validate()
    {
       // amount
       if ($this->amount <= 0 || $this->amount > 99999999999) {
           $this->errors[] = 'Amount too big or too low';
       }

       if(strlen($this->comment)>100){
        $this->errors[] = 'Komentarz może mieć maksymalnie 100 znaków';
       }
    }

    /**
     * Get payment method id
     * 
     * @return int 
     */
    public static function getPaymentMethodId($loggedUserId, $method){
        $sql = 'SELECT id FROM payment_methods_assigned_to_users WHERE user_id = :loggedUserId AND name = :method';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('loggedUserId', $loggedUserId, PDO::PARAM_INT);
        $stmt->bindValue('method', $method, PDO::PARAM_STR);

        // $stmt->setFetchMode(PDO::FETCH_OBJ);

        $stmt->execute();
        // $incomeCat = $stmt->fetch();
        $expenseCat = $stmt->fetchColumn();

        return $expenseCat;
    }

    /**
     * get category id
     * @return int
     */
    public static function getCategoryId($loggedUserId, $category){
        $sql = 'SELECT id FROM expenses_category_assigned_to_users WHERE user_id = :loggedUserId AND name = :category';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('loggedUserId', $loggedUserId, PDO::PARAM_INT);
        $stmt->bindValue('category', $category, PDO::PARAM_STR);

        // $stmt->setFetchMode(PDO::FETCH_OBJ);

        $stmt->execute();
        // $incomeCat = $stmt->fetch();
        $expenseCat = $stmt->fetchColumn();

        return $expenseCat;
    }

     /**
     * Update the user's profile
     * @param array $data Data from the edit profile form
     * @return boolean True if the data was updated, false otherwise
     */
    public function updateexpense($data)
    {   
        $this->id = $data['id'];
        $this->amount = $data['amount'];
        $this->category = $data['category'];
        $this->paymentMethod = $data['paymentMethod'];
        // echo $this->category;
        $this->date = $data['date'];
        $this->comment = $data['comment'];


        $this->user = Auth::getUser();
        $loggedUserId = $this->user->id;
        $expense_category_assigned_to_user_id = Expense::getCategoryId($loggedUserId, $this->category);
        $payment_method_assigned_to_user_id = Expense::getPaymentMethodId($loggedUserId, $this->paymentMethod);

        $this->validate();

        if (empty($this->errors)) {

            $sql = 'UPDATE expenses
                    SET expense_category_assigned_to_user_id = :expense_category_assigned_to_user_id,
                        amount = :amount,
                        payment_method_assigned_to_user_id = :payment_method_assigned_to_user_id,
                        date_of_expense = :date_of_expense,
                        expense_comment = :expense_comment
                        WHERE id = :id';


            $db = static::getDB();
            
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':expense_category_assigned_to_user_id', $expense_category_assigned_to_user_id, PDO::PARAM_INT);
            $stmt->bindValue(':payment_method_assigned_to_user_id', $payment_method_assigned_to_user_id, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_expense', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':expense_comment',$this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function deleteExpense($data){

        $this->id = $data['id'];

        $sql = 'DELETE FROM expenses WHERE id = :id';

        $db = static::getDB();
            
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        
        return $stmt->execute();

    }

    static public function getExpenseCategoriesOfUser(){
        $user = Auth::getUser();
        $loggedUserId = $user->id;
        
        $sql = 'SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = :loggedUserId';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('loggedUserId', $loggedUserId, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_OBJ);

        $stmt->execute();

        return $stmt->fetchAll();

    }

    /**
    * check if category exists
    * @return int
    */
    static private function checkIfCategoryExists($category, $loggedUserId){
       
        $sql = 'SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :loggedUserId AND name = :category';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('loggedUserId', $loggedUserId, PDO::PARAM_INT);
        $stmt->bindValue('category', $category, PDO::PARAM_STR);
        
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * add expense category
     * @return bool
     */
    public function addExpenseCategory($data){
        $this->user = Auth::getUser();
        $loggedUserId = $this->user->id;
        $categoryName = $data['newCategory'];

        if (!Expense::checkIfCategoryExists($categoryName, $loggedUserId)){
            $sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name) VALUES (:loggedUserId, :categoryName)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue('loggedUserId', $loggedUserId, PDO::PARAM_INT);
            $stmt->bindValue('categoryName', $categoryName, PDO::PARAM_STR);
            
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Delete all Expenses with given expense category id
     * @return bool
     */
    static public function deleteExpensesWithCertainCategory($expenseCategoryId){
        $user = Auth::getUser();
        $loggedUserId = $user->id;
        
        $sql = 'DELETE FROM expenses WHERE expense_category_assigned_to_user_id = :expenseCategoryId And user_id = :loggedUserId ';

        $db = static::getDB();
            
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':expenseCategoryId', $expenseCategoryId, PDO::PARAM_INT);
        $stmt->bindValue(':loggedUserId', $loggedUserId, PDO::PARAM_INT);
        
        
        return $stmt->execute();
    }

    /**
     * delete expense Category
     * @return bool
     */
    static public function deleteExpenseCategory($data){
        $expenseCategoryId = $data['id'];

        Expense::deleteExpensesWithCertainCategory($expenseCategoryId);

        $sql = 'DELETE FROM expenses_category_assigned_to_users WHERE id = :id';

        $db = static::getDB();
            
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $expenseCategoryId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * get payment methods of user
     * @return obj
     */
    static public function getPaymentMethodsOfUser(){
        $user = Auth::getUser();
        $loggedUserId = $user->id;
        
        $sql = 'SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = :loggedUserId';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('loggedUserId', $loggedUserId, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_OBJ);

        $stmt->execute();

        return $stmt->fetchAll();

    }

    /**
    * check if category exists
    * @return int
    */
    static private function checkIfPaymentMethodExists($method, $loggedUserId){
        
        $sql = 'SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :loggedUserId AND name = :method';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('loggedUserId', $loggedUserId, PDO::PARAM_INT);
        $stmt->bindValue('method', $method, PDO::PARAM_STR);
        
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * add expense category
     * @return bool
     */
    public function addPaymentMethod($data){
        $this->user = Auth::getUser();
        $loggedUserId = $this->user->id;
        $paymentMethod = $data['newMethod'];

        if (!Expense::checkIfPaymentMethodExists($paymentMethod, $loggedUserId)){
            $sql = 'INSERT INTO payment_methods_assigned_to_users (user_id, name) VALUES (:loggedUserId, :paymentMethod)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue('loggedUserId', $loggedUserId, PDO::PARAM_INT);
            $stmt->bindValue('paymentMethod', $paymentMethod, PDO::PARAM_STR);
            
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Delete all Expenses with given payment method id
     * @return bool
     */
    static public function deleteExpensesWithCertainPaymentMethod($paymentMethodId){
        $user = Auth::getUser();
        $loggedUserId = $user->id;
        
        $sql = 'DELETE FROM expenses WHERE payment_method_assigned_to_user_id = :paymentMethodId And user_id = :loggedUserId ';

        $db = static::getDB();
            
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':paymentMethodId', $paymentMethodId, PDO::PARAM_INT);
        $stmt->bindValue(':loggedUserId', $loggedUserId, PDO::PARAM_INT);
        
        
        return $stmt->execute();
    }
    


    /**
     * delete expense Category
     * @return bool
     */
    static public function deletePaymentMethod($data){
        $paymentMethodId = $data['id'];
        Expense::deleteExpensesWithCertainPaymentMethod($paymentMethodId);

        $sql = 'DELETE FROM payment_methods_assigned_to_users WHERE id = :id';

        $db = static::getDB();
            
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $paymentMethodId, PDO::PARAM_INT);
        
        
        return $stmt->execute();
    }


}