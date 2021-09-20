// console.log(expenses[0]);
    
    
function editIncome(clicked) {
    // console.log(clicked);
    var incomeId = clicked;
    $("#outer").css("display", "initial");
    
    console.log(incomeId);
    $("#editIncome").css("display", "initial");
    

    for (var i = 0; i<incomes.length; i++){
        if (incomes[i].id === incomeId){
            $('#amount').val(incomes[i].amount);
           
            document.getElementById('datePicker').valueAsDate = new Date(incomes[i].date_of_income);
            // var lenghtOfList = document.getElementById("category").options.length;

            $("#category option").each(function(){
                var thisOptionText = $(this).text();
                var thisOptionVal = $(this).val();

                if (thisOptionText === incomes[i].name){
                    $('#category').val(thisOptionVal);

                }

                // console.log(thisOptionValue);
            });
            $('#comment').val(incomes[i].income_comment);
            $('#income-id').val(incomeId);
            
            
            break;
        }
    }
}   

function deleteIncome(clicked) {
    $("#deleteIncome").css("display", "initial");
    $("#outer").css("display", "initial");
    var incomeId = clicked;
    $('#del-income-id').val(incomeId);
}



function closeWindow() {
    // console.log(clicked);
    $("#editIncome").css("display", "none");
    $("#editExpense").css("display", "none");
    $("#deleteIncome").css("display", "none");
    $("#deleteExpense").css("display", "none");
    $("#deleteIncomeCategory").css("display", "none"); 
    $("#deleteExpenseCategory").css("display", "none");
    $("#deletePaymentMethod").css("display", "none");
    $("#outer").css("display", "none");
}   





$('#outer').click(function() {
   
    $("#editIncome").css("display", "none"); 
    $("#deleteIncome").css("display", "none"); 
    $("#editExpense").css("display", "none");
    $("#deleteExpense").css("display", "none");
    $("#deleteIncomeCategory").css("display", "none");
    $("#deleteExpenseCategory").css("display", "none");
    $("#deletePaymentMethod").css("display", "none");
    $("#outer").css("display", "none");      
});

function editExpense(clicked) {
    // console.log(clicked);
    var expenseId = clicked;
    $("#outer").css("display", "initial");
    console.log(expenseId);
    $("#editExpense").css("display", "initial");
    

    for (var i = 0; i<expenses.length; i++){
        if (expenses[i].id === expenseId){
            $('#Expenseamount').val(expenses[i].amount);
           
            document.getElementById('expenseDatePicker').valueAsDate = new Date(expenses[i].date_of_expense);
            // var lenghtOfList = document.getElementById("category").options.length;

            $("#expenseCategory option").each(function(){
                var thisOptionText = $(this).text();
                var thisOptionVal = $(this).val();

                if (thisOptionText === expenses[i].name){
                    $('#expenseCategory').val(thisOptionVal);
                }

                // console.log(thisOptionValue);
            });

            $("#paymentMethod option").each(function(){
                var thisOptionText = $(this).text();
                var thisOptionVal = $(this).val();

                if (thisOptionText === expenses[i].payment_method){
                    $('#paymentMethod').val(thisOptionVal);
                }

                // console.log(thisOptionValue);
            });
            
            $('#expenseComment').val(expenses[i].expense_comment);
            $('#expense-id').val(expenseId);
            
            
            break;
        }
    }
}

function deleteExpense(clicked) {
    $("#deleteExpense").css("display", "initial");
    $("#outer").css("display", "initial");
    var expenseId = clicked;
    console.log(expenseId);
    $('#del-expense-id').val(expenseId);
}

function deleteIncomeCategory(clicked){
    $("#deleteIncomeCategory").css("display", "initial");
    $("#outer").css("display", "initial");
    var incomeCategoryId = clicked;
    console.log(incomeCategoryId);
     $('#del-category-income-id').val(incomeCategoryId);
}

function deleteExpenseCategory(clicked){
    $("#deleteExpenseCategory").css("display", "initial");
    $("#outer").css("display", "initial");
    var expenseCategoryId = clicked;
    console.log(expenseCategoryId);
    $('#del-category-expense-id').val(expenseCategoryId);
}

function deletePaymentMethod(clicked){
    $("#deletePaymentMethod").css("display", "initial");
    $("#outer").css("display", "initial");
    var paymentMethodId = clicked;
    console.log(paymentMethodId);
    $('#del-payment-method-id').val(paymentMethodId);
}