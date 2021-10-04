<?php
namespace App\Controllers;


use \Core\View;
use \App\Controllers\IncomeManager;
use \App\Models\Balance;

/**
 * Items controller (example)
 * Rozszerza autheticated, która rozszerza core/controller. 
 */
class BalanceManager extends Authenticated
{

    // protected function before()
    // {
    //     parent::before();
    //     $this->user = Auth::getUser();
    // }


    /**
     * Items index
     * @return void
     */
    // public function showAction(){
       
    //     // View::renderTemplate('BalanceManager/show.html', ['incomes' => $incomes]);
    //     $balance = new Balance ($_POST);
    //     $balance->save();
    //     View::renderTemplate('BalanceManager/show.html',['expenses'=>$balance->expenses, 'incomes'=>$balance->incomes, 'startDate'=>$balance->startDate, 'endDate'=>$balance->endDate, 'incomeRecords'=>$balance->incomeRecords, 'expenseRecords'=>$balance->expenseRecords,
    //     'incomeCategoryRecords'=>$balance->incomeCategoryRecords, 'balance'=>$balance->balance]);
    //     // $balance->clearCookies();
        
       
    // }

    public function addAction(){
        $balance = new Balance ($_POST);
        $balance->save();
        $balance->clearCookies();
        $incomesCategories = IncomeManager::getIncomesCategoryOfUser();
        $expenseCategories = ExpenseManager::getExpenseCategoriesOfUser();
        $paymentMethods = ExpenseManager::getPaymentMethodsOfUser();

        View::renderTemplate('BalanceManager/show.html',[
        'expenses'=>$balance->expenses, 
        'incomes'=>$balance->incomes, 
        'startDate'=>$balance->startDate, 
        'endDate'=>$balance->endDate, 
        'incomeRecords'=>$balance->incomeRecords, 
        'expenseRecords'=>$balance->expenseRecords,
        'incomeCategoryRecords'=>$balance->incomeCategoryRecords, 
        'balance'=>$balance->balance,
        'incomesAmountArray'=>$balance->incomesAmountArray,
        'incomesCategoryArray'=>$balance->incomesCategoryArray, 
        'sumOfIncomes'=>$balance->sumOfIncomes,
        'expensesAmountArray'=>$balance->expensesAmountArray,
        'expensesCategoryArray'=>$balance->expensesCategoryArray,
        'sumOfExpenses'=>$balance->sumOfExpenses,
        'expenseCategoryRecords'=>$balance->expenseCategoryRecords,
        'incomeCategories'=>$incomesCategories, 
        'expenseCategories'=>$expenseCategories,
        'paymentMethods' => $paymentMethods

    ]);
        

        // View::renderTemplate('BalanceManager/show.html',['expenses'=>$balance->expenses, 'incomes'=>$balance->incomes, 'startDate'=>$balance->startDate, 'endDate'=>$balance->endDate, 'incomeRecords'=>$balance->incomeRecords, 'expenseRecords'=>$balance->expenseRecords,
        // 'incomeCategoryRecords'=>$balance->incomeCategoryRecords, 'balance'=>$balance->balance,
        // 'sumOfIncomes'=>$balance->sumOfIncomes,
        // 'incomesCategoryArray'=>$balance->incomesCategoryArray,
        // 'incomesAmountArray'=>$balance->incomesAmountArray,
        // 'incomeCategoryRecords'=>$balance->incomeCategoryRecords,
        // 'sumOfExpenses'=>$balance->sumOfExpenses,
        // 'expenseCategoryRecords'=>$balance->expenseCategoryRecords,
        // 'expensesCategoryArray'=>$balance->expensesCategoryArray,
        // 'expensesAmountArray'=>$balance->expensesAmountArray]);
    }

    public function updateIncomeAction(){
        $incomeManager = new IncomeManager();
        $incomeManager->updateAction($_POST);
    }

    public function updateExpenseAction(){
        $expenseManager = new ExpenseManager();
        $expenseManager->updateAction($_POST);
    }

    public function deleteIncomeAction(){
        $incomeManager = new IncomeManager();
        $incomeManager->deleteAction($_POST);
    }

    public function deleteExpenseAction(){
        $expenseManager = new ExpenseManager();
        $expenseManager->deleteAction($_POST);
    }
}