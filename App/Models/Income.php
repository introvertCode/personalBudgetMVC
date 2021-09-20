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

class Income extends \Core\Model
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
            $income_category_assigned_to_user_id = Income::getCategoryId($loggedUserId, $this->category);
            
            $sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment) VALUES (:user_id, :income_category_assigned_to_user_id, :amount, :date_of_income, :income_comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            //name email i password to zmienne tworzone podczas tworzenia obiektu przez konstruktor $this->key=$value. Przypisujemy placeholderom ich wartość.
            $stmt->bindValue(':user_id', $this->user->id, PDO::PARAM_INT);
            $stmt->bindValue(':income_category_assigned_to_user_id', $income_category_assigned_to_user_id, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_income', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':income_comment',$this->comment, PDO::PARAM_STR);

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
    }

    /**
     * get category id
     * @return int
     */
        public static function getCategoryId($loggedUserId, $category){
        $sql = 'SELECT id FROM incomes_category_assigned_to_users WHERE user_id = :loggedUserId AND name = :category';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('loggedUserId', $loggedUserId, PDO::PARAM_INT);
        $stmt->bindValue('category', $category, PDO::PARAM_STR);

        // $stmt->setFetchMode(PDO::FETCH_OBJ);

        $stmt->execute();
        // $incomeCat = $stmt->fetch();
        $incomeCat = $stmt->fetchColumn();

        return $incomeCat;
    }


    /**
     * Update the user's profile
     * @param array $data Data from the edit profile form
     * @return boolean True if the data was updated, false otherwise
     */
    public function updateIncome($data)
    {   
        $this->id = $data['id'];
        $this->amount = $data['amount'];
        $this->category = $data['category'];
        echo $this->category;
        $this->date = $data['date'];
        $this->comment = $data['comment'];


        $this->user = Auth::getUser();
        $loggedUserId = $this->user->id;
        $income_category_assigned_to_user_id = Income::getCategoryId($loggedUserId, $this->category);

        $this->validate();

        if (empty($this->errors)) {

            $sql = 'UPDATE incomes
                    SET income_category_assigned_to_user_id = :income_category_assigned_to_user_id,
                        amount = :amount,
                        date_of_income = :date_of_income,
                        income_comment = :income_comment
                        WHERE id = :id';


            $db = static::getDB();
            
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':income_category_assigned_to_user_id', $income_category_assigned_to_user_id, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_income', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':income_comment',$this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function deleteIncome($data){

        $this->id = $data['id'];

        $sql = 'DELETE FROM incomes WHERE id = :id';

        $db = static::getDB();
            
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        
        return $stmt->execute();

    }

    static public function getIncomeCategoriesOfUser(){
        $user = Auth::getUser();
        $loggedUserId = $user->id;
        
        $sql = 'SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :loggedUserId';

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
        
        $sql = 'SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :loggedUserId AND name = :category';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('loggedUserId', $loggedUserId, PDO::PARAM_INT);
        $stmt->bindValue('category', $category, PDO::PARAM_STR);
        
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * addIncomeCategory
     * @return bool
     */
    public function addIncomeCategory($data){
        $this->user = Auth::getUser();
        $loggedUserId = $this->user->id;
        $categoryName = $data['newCategory'];

        if (!Income::checkIfCategoryExists($categoryName, $loggedUserId)){
            $sql = 'INSERT INTO incomes_category_assigned_to_users (user_id, name) VALUES (:loggedUserId, :categoryName)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue('loggedUserId', $loggedUserId, PDO::PARAM_INT);
            $stmt->bindValue('categoryName', $categoryName, PDO::PARAM_STR);
            
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Delete all Incomes with given income category id
     * @return bool
     */
    static public function deleteIncomesWithCertainCategory($incomeCategoryId){
        $user = Auth::getUser();
        $loggedUserId = $user->id;
        
        $sql = 'DELETE FROM incomes WHERE income_category_assigned_to_user_id = :incomeCategoryId And user_id = :loggedUserId ';

        $db = static::getDB();
            
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':incomeCategoryId', $incomeCategoryId, PDO::PARAM_INT);
        $stmt->bindValue(':loggedUserId', $loggedUserId, PDO::PARAM_INT);
        
        
        return $stmt->execute();
    }

    /**
     * delete income Category
     * @return bool
     */
    static public function deleteIncomeCategory($data){
        $incomeCategoryId = $data['id'];

        Income::deleteIncomesWithCertainCategory($incomeCategoryId);
        $sql = 'DELETE FROM incomes_category_assigned_to_users WHERE id = :id';

        $db = static::getDB();
            
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $incomeCategoryId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

}