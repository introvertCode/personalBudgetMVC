
// $(document).ready(function(){
    $("#show-bal").css("top", "220px");
	 
    $("#period").change(function(){
       let val = $("#period option:selected").text();
       // alert(val);
       if( val === "niestandardowy") {
           $("#form_container").height(360);
           $("#select-date").fadeIn(300);
           $("#show-bal").css("top", "420px");
       }
       else {
           $("#form_container").height(160);
           $("#select-date").fadeOut(300);
           $("#show-bal").css("top", "220px");
           
       }
     });