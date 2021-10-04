document.addEventListener('DOMContentLoaded', function() {
  // var incomeCategoryRecords = $('.js-incomeCategoryRecords').data('incomeCategoryRecords');



      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawChart1);
      google.charts.setOnLoadCallback(drawChart2);
 

      $(window).resize(function(){
        google.charts.setOnLoadCallback(drawChart);
        google.charts.setOnLoadCallback(drawChart1);
        google.charts.setOnLoadCallback(drawChart2);
        });
      
      // var sumOfIncomes = JSON.parse($.cookie("sumOfIncomes"));  
      // var incomeCategoryRecords = JSON.parse($.cookie("incomeCategoryRecords"));  
      // var incomesCategoryArray = JSON.parse($.cookie("incomesCategoryArray"));
      // var incomesAmountArray = JSON.parse($.cookie("incomesAmountArray"));

      // var sumOfExpenses = JSON.parse($.cookie("sumOfExpenses"));
      // var expenseCategoryRecords = JSON.parse($.cookie("expenseCategoryRecords"));  
      // var expensesCategoryArray = JSON.parse($.cookie("expensesCategoryArray"));
      // var expensesAmountArray = JSON.parse($.cookie("expensesAmountArray"));

      function drawChart() {
        
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('number', 'sa');
        data.addRows(incomeCategoryRecords);
        for (let i = 0; i < incomeCategoryRecords; i++){
            for (let j = 0; j<2; j++){
                if (j%2 == 0){
                    data.setCell(i, j, incomesCategoryArray[i] );
                }
                else{
                    data.setCell(i, j, incomesAmountArray[i] );
                }
            }
        }

        var options = {
          title: 'Przychody',
          backgroundColor: 'transparent',
          legend: { position: 'top', alignment: 'center' },
          chartArea: {height: 300}
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart-incomes'));

        chart.draw(data, options);
      }


      function drawChart2() {
        var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('number', 'sa');
            data.addRows(expenseCategoryRecords);
            for (let i = 0; i < expenseCategoryRecords; i++){
                for (let j = 0; j<2; j++){
                    if (j%2 == 0){
                        data.setCell(i, j, expensesCategoryArray[i] );
                    }
                    else{
                        data.setCell(i, j, expensesAmountArray[i] );
                    }
                }
            }

            var options = {
            title: 'Wydatki',
            backgroundColor: 'transparent',
            legend: { position: 'top', alignment: 'center' },
            chartArea: {height: 300}
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart-expenses'));

            chart.draw(data, options);
      }



      function drawChart1() {
        
        
        var data = google.visualization.arrayToDataTable([
          ['Rodzaj', 'Ilość'],
          ['Przychody',     sumOfIncomes],
          ['Wydatki',      sumOfExpenses],
          ]);
        //   sumOfIncomes
        var options = {
          title: 'Przychody i wydatki',
          backgroundColor: 'transparent',
          legend: { position: 'top', alignment: 'center' },
          chartArea: {height: 300}
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart-incomes-and-expenses'));

        chart.draw(data, options);

        // $.removeCookie('sumOfIncomes', { path: '/' });
        // $.removeCookie('incomeCategoryRecords', { path: '/' });
        // $.removeCookie('incomesCategoryArray', { path: '/' });
        // $.removeCookie('incomesAmountArray', { path: '/' });
        // $.removeCookie('sumOfExpenses', { path: '/' });
        // $.removeCookie('expenseCategoryRecords', { path: '/' });
        // $.removeCookie('expensesCategoryArray', { path: '/' });
        // $.removeCookie('expensesAmountArray', { path: '/' });
      }

     

    });
