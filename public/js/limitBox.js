
$(document).ready(function(){
    let id = $('.firstCat').attr('id');
    showLimitBox(id);
});


function showLimitBox(clicked){ 
    var categoryId = clicked;
    console.log(categoryId);
    // limitSet = -1;
    // $('#limit-span').html(expenseId);
    $.ajax("/ExpenseManager/showLimit", {
        type: 'POST',
        data: {categoryId: categoryId},
        dataType : "json",
        success: function(data){
            console.table(data);
            $('#limit-set').html(data[0].limit_set);
            limitSet = parseFloat(data[0].limit_set);
            if(data[0].limit_set){
                $('#limit-spent').html(data[0].sum);
                limitSpent =  parseFloat(data[0].sum);
                balance = data[0].limit_set - data[0].sum
                $('#limit-balance').html(balance);
                let amount = +$("#amount").val();
                var expenseAndLimitSpent = limitSpent + amount;
                $('#spent-and-amount').html(expenseAndLimitSpent);
            }
            // console.log(limitSet);
            if (limitSet){
                // $(".limitBox").show(200);
                $(".limitBox").slideDown();
                // $(".limitBox").css("display", "block");
                // $("#limitInfo").css("display", "block");
                $("#limitInfo").slideDown();
                
                
            } else {
                // $(".limitBox").css("display", "none");
                $(".limitBox").slideUp(); 
                // $("#limitInfo").css("display", "none");
                $("#limitInfo").slideUp();
            }

            $("#amount").keyup(function(){
   
                // console.log($( this ).val());
                let amount = +$( this ).val();
                var expenseAndLimitSpent = amount + limitSpent;

                $('#spent-and-amount').html(expenseAndLimitSpent);
                if (amount > balance || balance < 0) {
                    $(".limitBox").css("background-color", "rgba(224, 9, 9, 0.692)");
                } else {
                    $(".limitBox").css("background-color", "rgba(16, 224, 9, 0.692)");
                }
                
            });

            $('#spent-and-amount').html(expenseAndLimitSpent);
            
            if (amount > balance || balance < 0) {
                $(".limitBox").css("background-color", "rgba(224, 9, 9, 0.692)");
            } else {
                $(".limitBox").css("background-color", "rgba(16, 224, 9, 0.692)");
            }
            
            if(data[0].limit_set){
                possibleSpendings = limitSet - limitSpent;
                if (possibleSpendings > 0) {
                    $('#limitInfo').html("Możesz jeszcz wydać <b>" + possibleSpendings + "</b> w kategorii <b>" + data[0].name + "</b>");
                } else {
                    $('#limitInfo').html("Niestety, w tymi miesiącu limit wydatków w kategorii  <b>" + data[0].name + "</b> został osiągnięty");
                }
            }
           
           
        }
      

        
    });

}