$(document).ready(function() {

    function addLimit() { 
        // var id = clicked
        
        $("form" ).submit(function( event ) {
            console.log( $( this ).serializeArray() );
            event.preventDefault();
          });

        // $('#test').attr('id')
        // $("").load("add-limit.php");
    }
})