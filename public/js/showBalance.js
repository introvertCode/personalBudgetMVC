// let startDate = document.getElementById('start-date');
// balance = -10;
document.addEventListener('DOMContentLoaded', function() {
    var balance = $('.js-balance').data('balance');
// document.getElementById("start").innerHTML = startDate;

// document.getElementById("end").innerHTML = endDate;

document.getElementById("balance").innerHTML = balance;

let balanceStatement;

if (balance > 0){
    balanceStatement = "Bilans dodatni!";
    $("#balanceMSG").css("color", "#2bc244")
} else if(balance < 0){
    balanceStatement = "Bilans ujemny";
    $("#balanceMSG").css("color", "#e62e0e")
}else {
    balanceStatement = "Bilans zerowy";
    $("#balanceMSG").css("color", "#e6be0e")
}

document.getElementById("balanceMSG").innerHTML = balanceStatement;
});