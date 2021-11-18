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

class Balance extends \Core\Model
{

    /**
     * Class constructor
     * 
     * @param array $data Initial property values, opcjonalny ( = [] gdy nie podajemy wartości)
     * 
     * @return void
     */
    public function __construct($data = ['period'=>1]){
        foreach ($data as $key => $value) {
            
            //$this użyte do dynamicznego ustawiania atrybutu obiektu, w tym wypadku klucza jako właściwości obiektu i przypisanie do niego wartości.
            $this->$key=$value;
        };
    }
    
    /**
     * get Dates
     * @return void
     */
    public function getDate(){
        date_default_timezone_set ( 'Europe/Warsaw');
        // echo date("Y m d");
        $month = date("m");
        $day = date("d");
        $year = date("Y");
        // echo $month - 1;
        $startDate = date_create();
        $endDate = date_create();
        $firstDay = 1;
        $lastDay = $day;
        $startMonth = $month;
        $endMonth = $month;
        
        $ifperiodExists = isset($this->period);
        if ($ifperiodExists){
        $period = $this->period;
        } else {
            $period = 1;
        }

        
        if($period != 4){
                
            if($period == 2){
                $startMonth = $month-1;
                $endMonth = $startMonth;
                if($startMonth == 1||$startMonth == 3||$startMonth == 5||$startMonth == 7||$startMonth == 8||$startMonth == 10||$startMonth == 12){
                    $lastDay = 31;
                }elseif($startMonth == 2){
                    $isLeap = date('L');
                    if($isLeap) $lastDay = 29;
                    else $lastDay = 28;
                
                }else{
                    $lastDay = 30;
                }
                
            }elseif($period == 3){
                    $startMonth = 1;
            }
            
            date_date_set($startDate, $year, $startMonth, $firstDay);
            date_date_set($endDate, $year, $endMonth, $lastDay);
            $this->startDate = date_format($startDate,"Y-m-d");
            $this->endDate = date_format($endDate,"Y-m-d");

        } else{
            $this->startDate = $_POST['startDate'];
            $this->endDate = $_POST['endDate'];

        }
    }

    /**
     * set Incomes
     * @return Arr
     */
    public function setIncomes(){
        
        $this->getDate();
        $this->user = Auth::getUser();
        $loggedUserId = $this->user->id;
        $db = static::getDB();

        $incomeQuery = "SELECT incomes.id, incomes.income_category_assigned_to_user_id, incomes.amount, incomes.date_of_income, incomes.income_comment, incomes_category_assigned_to_users.name FROM incomes INNER JOIN incomes_category_assigned_to_users ON incomes_category_assigned_to_users.id = incomes.income_category_assigned_to_user_id  WHERE incomes.user_id = '$loggedUserId' AND incomes.date_of_income >= ' $this->startDate'AND incomes.date_of_income <= '$this->endDate' ORDER BY incomes.date_of_income DESC; " ;
        
        $incomeCategoryQuery = "SELECT incomes.id, incomes.income_category_assigned_to_user_id, sum(incomes.amount) As sum, incomes.date_of_income, incomes_category_assigned_to_users.name FROM incomes INNER JOIN incomes_category_assigned_to_users ON incomes_category_assigned_to_users.id = incomes.income_category_assigned_to_user_id  WHERE incomes.user_id = '$loggedUserId' AND incomes.date_of_income >= ' $this->startDate'AND incomes.date_of_income <= '$this->endDate' GROUP BY incomes.income_category_assigned_to_user_id ;" ;

        $incomeQueryStmt = $db->prepare($incomeQuery);
        $incomeCategoryQueryStmt = $db->prepare($incomeCategoryQuery);

        $incomeQueryStmt->execute();
        $incomeRecords = $incomeQueryStmt->rowCount();
        $this->incomeRecords = $incomeRecords;
        $incomeCategoryQueryStmt->execute();
        $incomeCategoryRecords = $incomeCategoryQueryStmt->rowCount();
        $this->incomeCategoryRecords = $incomeCategoryRecords;

        $incomeQueryStmt->setFetchMode(PDO::FETCH_OBJ);
        $incomeCategoryQueryStmt->setFetchMode(PDO::FETCH_OBJ);
       
        $this->incomes = $incomeQueryStmt->fetchAll();
        $this->incomesCategory = $incomeCategoryQueryStmt->fetchAll();
        
        // print("<pre>".print_r($this->incomesCategory,true)."</pre>");

        $this->sumOfIncomes = 0;
        if ($incomeRecords>=1) {
                    

            for ($i = 0; $i < $incomeRecords; $i++) {        
                $incomeAmount =  $this->incomes[$i]->amount;
                $this->sumOfIncomes += $incomeAmount;
                // $incomesArray[$a2]= $a3;              
            }
        }
        //  echo $this->sumOfIncomes;
        // $incomesAmountArray[]= null;
        $this->incomesAmountArray = 0;
        $this->incomesCategoryArray = 0;
         for ($i = 0; $i < $incomeCategoryRecords; $i++) {
            
            $incomeCategoryName = $this->incomesCategory[$i]->name;
            
            $incomeCategoryAmountSum = $this->incomesCategory[$i]->sum;
            
                
            $incomesAmountArray[]= $incomeCategoryAmountSum;
            $incomesCategoryArray[]= $incomeCategoryName;
            // $incomesArray [$a2] = $a3;

            $this->incomesAmountArray = $incomesAmountArray;
            $this->incomesCategoryArray = $incomesCategoryArray;

            // setcookie("incomesAmountArray", json_encode($incomesAmountArray));
            // setcookie("incomesCategoryArray", json_encode($incomesCategoryArray));

        }
        // $this->incomesAmountArray = $incomesAmountArray;
        // print("<pre>".print_r($incomesAmountArray,true)."</pre>");
        // print("<pre>".print_r($incomesCategoryArray,true)."</pre>");
        
        // setcookie("sumOfIncomes", json_encode($this->sumOfIncomes));  
             
       
        // setcookie("incomeCategoryRecords", json_encode($incomeCategoryRecords));

        
        
    }


   
    
    /**
     * Save the user model with the current property values
     * 
     * @return void
     */
    public function save(){
        $this->setIncomes();
        $this->setExpenses();
        $this->balance = $this->sumOfIncomes-$this->sumOfExpenses;
    }
    
    /**
     * Set expenses
     * 
     * @return void
     */
    public function setExpenses(){
        // $this->getDate();
        $this->user = Auth::getUser();
        $loggedUserId = $this->user->id;
        $db = static::getDB();

        // $expenseQuery = "SELECT expenses.id, expenses.expense_category_assigned_to_user_id, expenses.amount, expenses.date_of_expense, expenses.expense_comment, expenses_category_assigned_to_users.name FROM expenses INNER JOIN expenses_category_assigned_to_users ON expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id WHERE expenses.user_id = '$loggedUserId' AND expenses.date_of_expense >= ' $this->startDate' AND expenses.date_of_expense <= '$this->endDate'; " ;

        $expenseQuery = "SELECT expenses.id, expenses.expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, expenses.amount, expenses.date_of_expense, expenses.expense_comment, expenses_category_assigned_to_users.name, payment_methods_assigned_to_users.name As payment_method FROM expenses 
        INNER JOIN expenses_category_assigned_to_users ON expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id 
        INNER JOIN payment_methods_assigned_to_users ON payment_methods_assigned_to_users.id = expenses.payment_method_assigned_to_user_id 
        WHERE expenses.user_id = '$loggedUserId' AND expenses.date_of_expense >= ' $this->startDate' AND expenses.date_of_expense <= '$this->endDate' ORDER BY expenses.date_of_expense DESC; " ;

        $expenseCategoryQuery = "SELECT expenses.id, expenses.expense_category_assigned_to_user_id, sum(expenses.amount) As sum, expenses.date_of_expense, expenses_category_assigned_to_users.name 
        FROM expenses 
        INNER JOIN expenses_category_assigned_to_users ON expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id 
        WHERE expenses.user_id = '$loggedUserId' AND expenses.date_of_expense >= ' $this->startDate' AND expenses.date_of_expense <= '$this->endDate' 
        GROUP BY expenses.expense_category_assigned_to_user_id;";

//  
        
        $expenseQueryStmt = $db->prepare($expenseQuery);
        $expenseCategoryQueryStmt = $db->prepare($expenseCategoryQuery);

        $expenseQueryStmt->execute();
        $expenseRecords = $expenseQueryStmt->rowCount();
        $this->expenseRecords = $expenseRecords;
        $expenseCategoryQueryStmt->execute();
        $expenseCategoryRecords = $expenseCategoryQueryStmt->rowCount();
        $this->expenseCategoryRecords = $expenseCategoryRecords;

        $expenseQueryStmt->setFetchMode(PDO::FETCH_OBJ);
        $expenseCategoryQueryStmt->setFetchMode(PDO::FETCH_OBJ);
        // $incomeCategoryQueryStmt->setFetchMode(PDO::FETCH_OBJ);       

        $this->expenses = $expenseQueryStmt->fetchAll();
        $this->expensesCategory = $expenseCategoryQueryStmt->fetchAll();
        // print("<pre>".print_r($this->expenses,true)."</pre>");

        $this->sumOfExpenses = 0;
        if ($expenseRecords>=1) {
                    
            for ($i = 0; $i < $expenseRecords; $i++) {        
                $expenseAmount =  $this->expenses[$i]->amount;
                $this->sumOfExpenses += $expenseAmount;
                // $incomesArray[$a2]= $a3;              
            }
        }
         // echo $this->sumOfIncomes;
         $this->expensesAmountArray = 0;
         $this->expensesCategoryArray = 0;
         for ($i = 0; $i < $expenseCategoryRecords; $i++) {
            
            $expenseCategoryName = $this->expensesCategory[$i]->name;
            
            $expenseCategoryAmountSum = $this->expensesCategory[$i]->sum;
            
                
            $expensesAmountArray[]= $expenseCategoryAmountSum;
            $expensesCategoryArray[]= $expenseCategoryName;
            // $incomesArray [$a2] = $a3;
            
            $this->expensesAmountArray = $expensesAmountArray;
            $this->expensesCategoryArray = $expensesCategoryArray;
            // setcookie("expensesAmountArray", json_encode($expensesAmountArray));     
            // setcookie("expensesCategoryArray", json_encode($expensesCategoryArray));

        }
        // print("<pre>".print_r($incomesAmountArray,true)."</pre>");
        // print("<pre>".print_r($incomesCategoryArray,true)."</pre>");
        
        // setcookie("sumOfExpenses", json_encode($this->sumOfExpenses));
        
        // setcookie("expenseCategoryRecords", json_encode($expenseCategoryRecords));

    }

    /**
     * Clear cookies
     * 
     */
    public function clearCookies(){
        if (isset($_COOKIE["expensesAmountArray"])) {
            unset($_COOKIE['expensesAmountArray']); 
            setcookie('expensesAmountArray', null, -1, '/'); 
        }
        
        if (isset($_COOKIE['expensesCategoryArray'])) {
            unset($_COOKIE['expensesCategoryArray']); 
            setcookie('expensesCategoryArray', null, -1, '/'); 
        }
        
        if (isset($_COOKIE['sumOfExpenses'])) {
            unset($_COOKIE['sumOfExpenses']); 
            setcookie('sumOfExpenses', null, -1, '/'); 
        }

        if (isset($_COOKIE['expenseCategoryRecords'])) {
            unset($_COOKIE['expenseCategoryRecords']); 
            setcookie('expenseCategoryRecords', null, -1, '/'); 
        }

        if (isset($_COOKIE['incomesAmountArray'])) {
            unset($_COOKIE['expensesAmountArray']); 
            setcookie('expensesAmountArray', null, -1, '/'); 
        }
        
        if (isset($_COOKIE['incomesCategoryArray'])) {
            unset($_COOKIE['expensesCategoryArray']); 
            setcookie('expensesCategoryArray', null, -1, '/'); 
        }
        
        if (isset($_COOKIE['sumOfIncomes'])) {
            unset($_COOKIE['sumOfExpenses']); 
            setcookie('sumOfExpenses', null, -1, '/'); 
        }

        if (isset($_COOKIE['incomeCategoryRecords'])) {
            unset($_COOKIE['expenseCategoryRecords']); 
            setcookie('expenseCategoryRecords', null, -1, '/'); 
        }
    }

    

    
    

   
}