/*
  Theme Name: Australia Post - Postcode & Movers Statistics Administration
  Author: Lucas Jordan
  Description: Product add scripts
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
    $("#nav-main #nav-products > a").addClass("active");

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


    /* Free tier checkbox function */
    $('#enableFree').change(function() {

        $freeTier = $(".free_tier");

        if($(this).is(":checked")) {
            $freeTier.css("display", "block");
        } else {
            $freeTier.css("display", "none");
        }

    });

});


/*-----------------------------------------------------------------------------
#   Window Onload Function
-----------------------------------------------------------------------------*/
window.onload = function () {}
