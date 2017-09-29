jQuery( document ).ready( function( $ ) {
    /**
     * Check if the searchfield has a value. If it has, set the class active
     */
    $('header .searchbar form input').on('keyup', function(){
        if($(this).val()){
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    });
} );