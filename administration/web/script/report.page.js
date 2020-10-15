/*
  Theme Name: Australia Post - Postcode & Movers Statistics Administration
  Author: Lucas Jordan
  Description: Reports page javascript
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Initialize Foundation
#   Document Ready Function
#   Window Onload Function
--------------------------------------------------------------*/

/*--------------------------------------------------------------
#   Initialize Foundation
--------------------------------------------------------------*/
$(document).foundation()


/*-----------------------------------------------------------------------------
#   Document Ready Function
-----------------------------------------------------------------------------*/
$(document).ready( function() {

    /* Set page nav item to active */
    $("#nav-main #nav-report > a").addClass("active");

    /*  On load ensure the wait spinner is displayed */
	$("#waitSpinner").addClass("fixed");

    /* File upload script */
    /* https://tympanus.net/codrops/2015/09/15/styling-customizing-file-inputs-smart-way/ */
    $( '.inputfile' ).each( function()
    {
        var $input	 = $( this ),
            $label	 = $input.next( 'label' ),
            labelVal = $label.html();

        $input.on( 'change', function( e )
        {
            var fileName = '';

            if( this.files && this.files.length > 1 )
                fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
            else if( e.target.value )
                fileName = e.target.value.split( '\\' ).pop();

            if( fileName )
                $label.find( 'span' ).html( fileName );
            else
                $label.html( labelVal );
        });

        // Firefox bug fix
        $input
        .on( 'focus', function(){ $input.addClass( 'has-focus' ); })
        .on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
    });

});


/*-----------------------------------------------------------------------------
#   Window Onload Function
-----------------------------------------------------------------------------*/
window.onload = function () {

    /* Once page has fully finished loading hide the wait spinner */
    setTimeout( function(){
        $("#waitSpinner").removeClass("fixed");
    },600);
}
