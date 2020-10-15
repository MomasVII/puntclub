/*
  Theme Name: Raremedia Tagbox Class
  Author: Lucas Jordan
  Description: Raremedia Tagbox Class
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Tagbox documentation
#   Tagbox class constructor
#   Tagbox update badges
#   Tagbox bind add tag function
#   Tagbox bind remove tag function
--------------------------------------------------------------*/

/*--------------------------------------------------------------
#   Tagbox documentation
----------------------------------------------------------------
target = parent     // STRING Tagbox Target the root dom element in which the Navigation object is bound to
type = "string"  // STRING Tagbox input field input type | options | string, postcode
--------------------------------------------------------------*/

/*--------------------------------------------------------------
#  Tagbox class constructor
--------------------------------------------------------------*/
function Tagbox(options) {

    /* If no target is passed we simply return */
    if (typeof options.target === 'undefined') {
        return;
    }

    /* Check to ensure new keyword was used to make sure obect is instance of Tagbox */
    if (!(this instanceof Tagbox)) {
        return new Tagbox(options);
    }

    /* Set the Tagbox parameters */
    this.target = (typeof options.target !== 'undefined') ? options.target : 1;
    this.type = (typeof options.type !== 'undefined') ? options.type : "string";

    this.updateTagBox();


    /*  Set timeout function to deplay element binding to
    *   allow time to generate the badges before binding
    *   events to them
    */
    setTimeout(function() {

        this.bindAdd( this );
        this.bindRemove( this );

    }.bind(this), 50);

	/* Bind enter button to input-field to allow eneter to add an item */
	$(this.target).find(".input-field").on("keypress", function( event ){
       if ( event.which == 13 ) {
		   event.preventDefault();
		   event.stopPropagation();
           var $element = $(this.target);
           this.add(this, $element );
       }
	}.bind(this));

}

/*--------------------------------------------------------------
#   Tagbox update badges
--------------------------------------------------------------*/
Tagbox.prototype.updateTagBox = function() {

    /* Set variables */
    var $target = this.target;
    var value = $target.find(".value-field").val()
    $target.find(".tag-list").html("");

    /*  If we have some value we use  commars as a delimiter ","
    *   and add a badge for each postcode
    */
    if ( value  !== '' ) {

        var valArray = value.split(",");

        for ( var i = 0; i < valArray.length; i++ ) {
            /* Check is item contains a postcode then append a new badge element */
            if ( valArray[i] !== "" ) {
                $target.find(".tag-list").append('<div class="badge white aqua-dark-bg" id="'+this.target[0].id+'-badge-'+i+'" title="Click to remove postcode"><span>'
                    +valArray[i]
                    +'</span><i class="icons icons-close white"></i></div>'
                );
            }

        }

    }

    this.bindRemove( this );
}


/*--------------------------------------------------------------
#   Tagbox add tag function
--------------------------------------------------------------*/
Tagbox.prototype.add = function(target, $element) {

    var thisType = this.type;

    /* Set variables */
    var $inputField = $element.find(".input-field");
    var $valueField = $element.find(".value-field");
    var $valueFieldValue = $element.val();

    /* If tag box is a dropdown table check selected option has value */
    if (  $inputField.get(0).tagName == 'SELECT') {
        var selectedVal = $($inputField).find("option:selected").attr("value");
        if ( selectedVal == 'undefined' || selectedVal == undefined ) {
            return;
        }

    /* if we enter a blank value we return */
    } else if ( $inputField.val() == "" || $inputField.val() == null ) {
        return;
    }
    /* Check if we entered a value and value doesn alreay exist */
    if ( $valueField.val().indexOf( $inputField.val() ) !== -1 ) {
        return;
    }

    /* Validate our input value */
    switch( thisType ) {

        case "string":

            /* If input field has some value we appened it to the value field */
            if ( $inputField.val() !== "" ) {

                if (  $inputField.get(0).tagName == 'SELECT') {

                    var selectedVal = $($inputField).find("option:selected").attr("value");

                    $valueFieldValue = $element.find(".value-field").val() + selectedVal + ",";
                    $valueField.val($valueFieldValue).attr("value", $valueFieldValue);
                    $inputField.removeClass("is-invalid-input");

                } else {

                    $valueFieldValue = $valueFieldValue + $inputField.val() + ",";
                    $valueField.val($valueFieldValue).attr("value", $valueFieldValue);
                    $inputField.removeClass("is-invalid-input").val("");

                }

                /* Update our tag-box */
                target.updateTagBox();

            } else {
                $inputField.addClass("is-invalid-input");
            }

            break;
        case "postcode":

            /* If input field has some value and is a valid postcode we appened it to the value field */
            if ( $inputField.val() !== "" && $inputField.val().length == 4 && !isNaN($inputField.val() )  ) {

                $valueFieldValue = $valueFieldValue + $inputField.val() + ",";
                $valueField.val($valueFieldValue).attr("value", $valueFieldValue);
                $inputField.removeClass("is-invalid-input").val("");
                /* Update our tag-box */
                target.updateTagBox();

            } else {
                $inputField.addClass("is-invalid-input");
            }

            break;
    }

}


/*--------------------------------------------------------------
#   Tagbox bind add tag function
--------------------------------------------------------------*/
Tagbox.prototype.bindAdd = function(target) {

    var $element = $(target.target);
    var thisType = this.type;
    $element.find('.button').on("click", function(){

        this.add(this, $element );

    }.bind(this));
}


/*--------------------------------------------------------------
#   Tagbox bind remove tag function
--------------------------------------------------------------*/
Tagbox.prototype.bindRemove = function(target) {

    var $element = $(target.target);

    $element.find('.badge').each( function(){

        var $this = $(this);

        $this.on("click", function(){

            /* Obtain badge value and remove it from the value field value */
            var value = $this.find('span').html();
            var str = $element.find(".value-field").val();
            var res = str.replace( value + ",", "");
            $element.find(".value-field").val(res).attr("value", res);
            /* Update our tag-box */
            target.updateTagBox();

        });

    });

}
