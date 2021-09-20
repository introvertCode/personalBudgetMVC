/**
 * Ass jQuery Validation plugin method for a valid password
 * 
 * Valid passwords contain at least one letter and one number.
 */
 //custom validation method from jquery validation plugin
 $.validator.addMethod('validPassword', 
 function(value, element, param){
     if (value !='') {
         if(value.match(/.*[a-z]+.*/i) == null){
             return false;
         }
         if (value.match(/.*\d+.*/) == null){
             return false;
         } 
     }
     return true;
 },
     'Must contain at least one letter and one number'
);

$.validator.addMethod('validAmount', 
function(value, element, param){
    
        if(value <= 0 || value > 99999999999 ){
            return false;
        }
    

    return true;
},
    'Wartość musi być większa od 0 i mniejsza niż 99999999999'
);

$.validator.addMethod('validComment', 
function(value, element, param){
    
        if(value.length > 100 ){
            return false;
        }
    

    return true;
},
    'Komentarz może mieć maksymalnie 100 znaków'
);

$.validator.addMethod('validLength', 
function(value, element, param){
    
        if(value.length > 50 ){
            return false;
        }
    

    return true;
},
    'Nazwa może mieć maksymalnie 50 znaków'
);