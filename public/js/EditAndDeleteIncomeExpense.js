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
    $("#editIncomeCategoryDiv").css("display", "none"); 
    $("#deleteExpenseCategory").css("display", "none");
    $("#deletePaymentMethod").css("display", "none");
    $("#addLimitDiv").css("display", "none");
    $("#editPaymentMethodDiv").css("display", "none"); 
    $("#outer").css("display", "none");
}   





$('#outer').click(function() {
   
    $("#editIncome").css("display", "none"); 
    $("#deleteIncome").css("display", "none"); 
    $("#editExpense").css("display", "none");
    $("#deleteExpense").css("display", "none");
    $("#editIncomeCategoryDiv").css("display", "none");
    $("#deleteExpenseCategory").css("display", "none");
    $("#deletePaymentMethod").css("display", "none");
    $("#addLimitDiv").css("display", "none");
    $("#editPaymentMethodDiv").css("display", "none");     
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
    $("#editIncomeCategoryDiv").css("display", "initial");
    $("#outer").css("display", "initial");
    var incomeCategoryId = clicked;
    console.log(incomeCategoryId);
    $('#del-category-income-id').val(incomeCategoryId);
    $('#edit-category-income-id').val(incomeCategoryId);

    for (var i = 0; i<incomeCategories.length; i++){
        if (incomeCategories[i].id === incomeCategoryId){
            $('#income-category-name').val(incomeCategories[i].name)
            $('#removeIncomeCategoryName').html("Czy usunąć kategorię " + incomeCategories[i].name +"?");
        }
    }
}

function addLimitDiv(clicked){
    $("#addLimitDiv").css("display", "initial");
    $("#outer").css("display", "initial");

    $("#deleteExpenseCategory").css("display", "initial");
    $("#outer").css("display", "initial");
    var expenseCategoryId = clicked;
    // console.log(expenseCategoryId);

    $('#ErrorFormIncomeName').html("");
    
    $('#del-category-expense-id').val(expenseCategoryId);
    $('#del-category-expense-id-limit').val(expenseCategoryId);
    
    // var expenseCategoryId = clicked;
    for (var i = 0; i<expenseCategories.length; i++){
        if (expenseCategories[i].id === expenseCategoryId){
            $('#expense-category-name').val(expenseCategories[i].name)
            $('#Limit').val(expenseCategories[i].limit_set);
            $('#setLimitFor').html("Ustaw limit dla kategorii " + expenseCategories[i].name);
            $('#removeCategoryName').html("Czy usunąć kategorię " + expenseCategories[i].name +"?");
            
            if (expenseCategories[i].limit_set) {
                $('#setLimitCheckBox').prop('checked', true);
                document.getElementById('Limit').disabled = false;
            } else {
                $('#setLimitCheckBox').prop('checked', false);
                document.getElementById('Limit').disabled = true;
            }
        }
    }

    console.log(expenseCategoryId);
    
}

function activateLimit() {   
    
    if (document.getElementById('setLimitCheckBox').checked) {
        document.getElementById('Limit').disabled = false;
        
        
    } else {
        document.getElementById('Limit').disabled = true;
        document.getElementById('Limit').value = "";
    }
    
}

function deletePaymentMethod(clicked){
    $("#editPaymentMethodDiv").css("display", "initial");
    $("#outer").css("display", "initial");
    var paymentMethodId = clicked;
    console.log(paymentMethodId);
    $('#del-payment-method-id').val(paymentMethodId);
    $('#edit-payment-method-id').val(paymentMethodId);
    

    for (var i = 0; i<paymentMethods.length; i++){
        if (paymentMethods[i].id === paymentMethodId){
            $('#payment-method-name').val(paymentMethods[i].name)
            $('#removePaymentMethodName').html("Czy usunąć Metodę " + paymentMethods[i].name +"?");
        }
    }
}

function addLimit() { 
   
   
    // var id = clicked
    
    $("form").submit(function( event ) {
       var limit = -1;
       var Expense =  $( this ).serializeArray();
       var id = Expense[0].value
       var name = Expense[1].value

        
            if (Expense.length > 3) {
                limit = Expense[3].value;
                // limit = parseFloat(limit).toFixed(2);
                // console.log( limit );
            }
        


        if (name.length < 1 || limit < -1 || limit > 99999999999) {
            return;
        } else {

            console.log( Expense );
            event.preventDefault();
            
            htmlId = "exp" + id;
            htmlCatId = "exp-cat-" + id;
            console.log( htmlId );
        
            console.table(expenseCategories);
            
            $.ajax("/profile/setExpenseLimit", {
                type: 'POST',
                data: {limit: limit, id: id, name: name},
                complete: function(){
                    var categoryExists = false;
                    var validName = "";
                    if (limit === -1){
                        $('#' + htmlId).html("Limit: -" );
                    } else {
                        $('#' + htmlId).html("Limit: " + limit);
                    }
                    
                    for (var j = 0; j < expenseCategories.length; j++){

                        if(expenseCategories[j].id === id){
                            validName = expenseCategories[j].name;
                        }

                        
                        if (expenseCategories[j].name === name){
                            if(expenseCategories[j].id !== id){
                                $('#ErrorFormIncomeName').html("Błąd! Istnieje już taka kategoria");
                                categoryExists = true;
                                break;
                            } else {
                                $('#ErrorMSG').html("Zmieniono pomyślnie!");
                                validName = name;
                                closeWindow();
                            }
                        }
                        // console.log(validName);

                    }

                    if (!categoryExists){
                        $('#ErrorMSG').html("Zmieniono pomyślnie!");
                        validName = name;
                        closeWindow();
                    }

                    console.log(validName);
                    
                    $('#' + htmlCatId).html(validName);

                    if (!categoryExists){                 
                        for (var i = 0; i < expenseCategories.length; i++){
                            if (expenseCategories[i].id === id){
                                expenseCategories[i].name = validName;
                            
                                if (limit === -1) {
                                    expenseCategories[i].limit_set = null;
                                } else {
                                    expenseCategories[i].limit_set = limit;
                                }
                            }
                        }
                    }
                    
                
                },
                error: function(){
                    console.log("błąd");
                }
                
            });
                
        }

                

                
            // $('#' + htmlId).html("Limit: " + limit);
            // limit = 0;
            //     $('#' + htmlId).html(limit);
            
            
            //     $('#' + htmlId).load("setExpenseLimit", {
            //     limit: limit,
            //     id: id
            //     });
            // // }
            
            
    });

    // 

    
}