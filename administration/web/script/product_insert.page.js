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
$(document).ready(function() {

    /* Set page nav item to active */
    $("#nav-main #nav-products > a").addClass("active");


    /*--------------------------------------------------------
    #   Initialize CKEditor
    --------------------------------------------------------*/
    CKEDITOR.replace("description");
    CKEDITOR.replace("sample_data");
    CKEDITOR.replace("paid_tier_terms");
    CKEDITOR.replace("free_tier_terms");


    /*--------------------------------------------------------
    #   Initialize state tag box
    --------------------------------------------------------*/
    if ( $("#stateTagBox").length > 0 ) {
        var stateTagBox = new Tagbox({
            target: $("#stateTagBox")
        });
    }


    /*--------------------------------------------------------
    #   Initialize postcode tag box
    --------------------------------------------------------*/
    if ( $("#postcodeTagBox").length > 0 ) {
        var postcodeTagBox = new Tagbox({
            target: $("#postcodeTagBox")
        });
    }


    /*--------------------------------------------------------
    #   Custom input type="file" function
    --------------------------------------------------------*/
    /* https://tympanus.net/codrops/2015/09/15/styling-customizing-file-inputs-smart-way/ */
    $('.inputfile').each(function() {
        var $input = $(this),
            $label = $input.next('label'),
            labelVal = $label.html();

        $input.on('change', function(e) {
            var fileName = '';

            if (this.files && this.files.length > 1) {
                fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
            } else if (e.target.value) {
                fileName = e.target.value.split('\\').pop();
            }
            if (fileName) {

                /*  If we add a paidfile and we have the option to
                *   Append/replace existing data we need to Make
                *   Append/replace existing data radio button required
                */
                if ( $input.attr("id") == "paidFile" && $('input[name="append_replace"]').length > 0 ) {
                    $('input#appendData').prop('required',true);
                }

                $label.find( 'span' ).html( fileName );
                $label.find( 'span' ).addClass( 'dark' );
            } else {
                $label.html(labelVal);
                $label.find('span').removeClass('dark');

                /*  If we remove a paidfile and we have the option to
                *   Append/replace existing data we need to Make
                *   Append/replace existing data radio button not required
                */
                if ( $input.attr("id") == "paidFile" && $('input[name="append_replace"]').length > 0 ) {
                    $('input#appendData').removeAttr('required');
                }
            }
        });

        // Firefox bug fix
        $input
            .on('focus', function() {
                $input.addClass('has-focus');
            })
            .on('blur', function() {
                $input.removeClass('has-focus');
            });
    });

	/* Onload check if free tier enabled */
	if ( $('#enable_free_tier').is(":checked") ) {
		$(".free_tier").css("display", "block");
	}
    /*--------------------------------------------------------
    #   Enable free tier checkbox function
    --------------------------------------------------------*/
    $('#enable_free_tier').change(function() {

        $freeTier = $(".free_tier");

        if ($(this).is(":checked")) {
            $freeTier.css("display", "block");
        } else {
            $freeTier.css("display", "none");
        }

    });


    /*--------------------------------------------------------
    #   Product Accordion Checkbox enable / disable function
    --------------------------------------------------------*/
    $('.accordionEnabler').each(function() {

        var $input = $(this);

        if ( $input[0].hasAttribute("data-target") ) {

            var $accordion = $( "#" + $input.attr("data-target") );

            /* Onload Checkbox is checked enable the accordion */
            if ( $input.is(':checked') ) {

                /* Toggle accordion open */
                $accordion.foundation('down', $( "#" + $input.attr("data-target") + "-content" ) );

            /* Onload Disable the accordion */
            } else {

                /* Toggle accordion close */
                $accordion.foundation('up', $( "#" + $input.attr("data-target") + "-content" ) );

                /* On close disable payment fields */
                $accordion.closest(".product-accordion").find(".enableBox").each( function(){

                    if ( $(this).prop('checked') == true ) {
                        $(this).click();
                    }

                })
            }

            $input.on('change', function(e) {

                /* Checkbox is checked enable the accordion */
                if ( $input.is(':checked') ) {

                    /* Toggle accordion open */
                    $accordion.foundation('down', $( "#" + $input.attr("data-target") + "-content" ) );

                /* Disable the accordion */
                } else {

                    /* Toggle accordion close */
                    $accordion.foundation('up', $( "#" + $input.attr("data-target") + "-content" ) );

                    /* On close disable payment fields */
                    $accordion.closest(".product-accordion").find(".enableBox").each( function(){

                        if ( $(this).prop('checked') == true ) {
                            $(this).click();
                        }

                    })
                }

            });
        }

    });


    /*--------------------------------------------------------
    #   Product Accordion enable / disable function
    --------------------------------------------------------*/
    $(".product-accordion .accordion-title").each(function() {

        var $input = $(this);

        if ( $input[0].hasAttribute("data-target") ) {

            var $checkbox = $( "#" + $input.attr("data-target") );

            $input.on("click", function( event ){

                if ( $input.attr("aria-expanded") == "true" ) {
                    $checkbox.attr('checked', true);
                    $checkbox.prop('checked', true);
                } else {
                    $checkbox.attr('checked', false);
                    $checkbox.prop('checked', false);

                    /* On close disable payment fields */
                    $input.closest(".product-accordion").find(".enableBox").each( function(){
                        var pathArray = window.location.pathname.split( '/' );
                        if ( pathArray[2] !== 'product_update.html' ) {
                            if ( $(this).prop('checked') == true ) {
                                $(this).click();
                            }
                        }

                    })
                }

            })

        }

    });


    /*--------------------------------------------------------
    #   Enable / disable pricing function
    --------------------------------------------------------*/
    $(".enableContainer .enableBox").each(function() {

        var $input = $(this);

        if ( $input.is(':checked') ) {
            $input.closest(".enableContainer").attr("aria-expanded", "true");
            $input.closest(".enableContainer").find(".enableItem").slideDown( "slow", function() {});
            $input.closest(".enableContainer").find(".enableItem input").attr("required", true);
        }

        $input.on('change', function(e) {

            /* Checkbox is checked enable the accordion */
            if ( $input.is(':checked') ) {

                $input.closest(".enableContainer").attr("aria-expanded", "true");
                $input.closest(".enableContainer").find(".enableItem").slideDown( "slow", function() {});
				$input.closest(".enableContainer").find(".enableItem input").attr("required", true);

            /* Disable the accordion */
            } else {

                $input.closest(".enableContainer").find(".enableItem").slideUp( "slow", function() {});
                $input.closest(".enableContainer").find(".enableItem input").removeAttr("required").val("");
                setTimeout( function(){
                    $input.closest(".enableContainer").attr("aria-expanded", "false");
                }, 500);
            }

        });

    });

});


/*-----------------------------------------------------------------------------
#   Window Onload Function
-----------------------------------------------------------------------------*/
window.onload = function() {}
