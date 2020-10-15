/*
 EXAMPLE

 $(document).ready(function() {
	 $("#submit").click(function(e) {
	 return form_validator(false);
	 });
 });

Inputs that require validation require the validateMe class and the type of validation required
 <input class='validateMe val-email' id='supportEmail' name='supportEmail' type='text' value='' />

 displayResponse - parameter sets if a message is echoed into the #response div. Default = true

 scrollToTop - parameter determines whether the page scrolls to the top if there is an error. Default = true

 */
var lettersValid = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

function form_validator(targetElement, displayResponse, scrollToTop)
{
    targetElement = typeof targetElement !== 'undefined' ? targetElement : "#form-errors";
    scrollToTop = typeof scrollToTop !== 'undefined' ? scrollToTop : true;
    displayResponse = typeof displayResponse !== 'undefined' ? displayResponse : true;

    var isValid = true; //Is the field valid (each loop)
	var isAllValid = true; //Are all fields valid (any invalid field will trigger this as false)

    var errorMessage = '';
	// Reset error formatting and html

    $('.error-message').remove();
	$('.inline-error-symbol').remove();
	$('.has-error').removeClass("has-error");
	$(".form-errors").hide();

    $(".validateMe").each(function () {
        var input = $(this);
        var val = "null";

		// Find the validation type in the classes
		var classList =$(this).attr('class').split(/\s+/);
		$.each(classList, function(index, item){
			if (item.indexOf("val-") == 0) {
			   val = item;
			}
		});

        if(val == "val-notBlank") {
            if(input.val() == "") {
                if(input.is("input:text") || input.is("input:password") || input.is("textarea")) {
                    errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please fill in this information.</div>';
                    isValid = false;
                }
                if(input.is("select")) {
                    errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please select an option.</div>';
                    isValid = false;
                }
                if(input.is("input:file")) {
                    errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please select a file.</div>';
                    isValid = false;
                }
            }
        };

        if(val == "val-numberCompulsory") {
            if(input.val() == "" || isNaN(input.val())) {
                errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please enter a number.</div>';
                isValid = false;
            }
        };

        if(val == "val-emailCompulsory" || val == "val-email") {
            if(input.val() == "" && val == "val-emailCompulsory")
            {
				if(val == "val-emailCompulsory"){
					errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please fill in your email address.</div>';
					isValid = false;
				} else {
					//Do nothing
				}
			}
			else
			{
                var fieldValue = input.val();

                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if(!emailReg.test(fieldValue))
                {
                    errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Email address is not valid.</div>';
                    isValid = false;
                }
            }
        }


        if(val == "val-phone" || val == "val-mobile" || val == "val-phoneCompulsory" || val == "val-mobileCompulsory") {

            var fieldValue = removeSpaces(input.val());

           if(fieldValue == "") {
				if(val == "val-phoneCompulsory" || val == "val-mobileCompulsory"){
					errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please fill in your phone number.</div>';
					isValid = false;
				} else {
					// Do nothing
				}
            }
                if(fieldValue.length < 10) {
                    errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please enter at least 10 digits.</div>';
                    isValid = false;
                } else {
                    if(allNumbers(fieldValue) == false) {
                        errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please enter numbers only.</div>';
                        isValid = false;
                    }

                    input.val(removeSpaces(input.val()));
                }
        }


        if(val == "val-radioSelect") {

			var radios = $('input[name='+$(this).attr("name")+']');
			var fieldValue = radios.filter(':checked');
            if(fieldValue.length == 0) {
                errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please select an option.</div>';
                isValid = false;
            }
        }

        if(val == "val-checked") {
            if(input.prop('checked') == false) {
				errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please confirm before proceeding.</div>';
				isValid = false;
            }
        };


        if(val == "val-notFirst"){
            if(input[0].selectedIndex == 0) {
                errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please select an option.</div>';
                isValid = false;
            }
        }


        if(val == "val-creditCard") {
			var fieldValue = removeSpaces(input.val());
            if(fieldValue.length < 14 || fieldValue.length > 16) {
				errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please enter a valid credit card number.</div>';
				isValid = false;
			} else {
				if(allNumbers(fieldValue) == false) {
					errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please enter numbers only.</div>';
					isValid = false;
				}
			}
            if(input.val() == "") {
				errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please enter a credit card number.</div>';
				isValid = false;
            }
        };

        /*---- CUSTOM VALIDATION OF PRODUCT PAGE ------*/
        if(val == "val-mustHaveOne") {

            var co = Number($("#costOnceOff").val())>0? 1:0;
            var cq = Number($("#costQuarterly").val())>0? 1:0;
            var cm = Number($("#costMonthly").val())>0? 1:0;
            var eo = $("#enabledOnceOff:checkbox:checked").length;
            var eq = $("#enabledQuarterly:checkbox:checked").length;
            var em = $("#enabledMonthly:checkbox:checked").length;
            var oTot =co + eo;
            var qTot =cq + eq;
            var mTot =cm + em;

            if(oTot <2 && qTot <2 && mTot <2) {
                errorMessage = '<div class="inline-error-symbol"></div><div class="error-message">Please ensure at least one price is entered and enabled.</div>';
                isValid = false;
            }
        };

		if(isValid == false){
			isAllValid = false;
		}

        if(isValid == false && displayResponse == true) {

			if(val == "val-radioSelect") {
			    $("#inputPermDurations").append(errorMessage);
				$("#inputPermDurations").addClass('has-error');
			} else {
			    input.parent().append(errorMessage);
				input.parent().addClass('has-error');
			}

        }

		isValid = true;

    });

    if(isAllValid == false) {
        errorMessage += "</ul>";
        if(scrollToTop == true) {
            $('html, body').animate({scrollTop:0}, 'normal');
            $('#response').html('<div class="form-errors"><h3>Error</h3>Please fill out the relevant details below.</div>');
            $(".form-errors").show();
        }
        return false;
    } else {
        return true;
    }
}

function removeSpaces(string) {
    return string.split(' ').join('');
}

function allNumbers(val) {
    var validNums = "+0123456789()";
    var allNumbers = true;
    for(var i=0; i < val.length; i++) {
        temp = "" + val.substring(i, i+1);
        if (validNums.indexOf(temp) == "-1") {
            allNumbers = false;
        }
    }
    return allNumbers;
}

/*--------------------------------------------------------
#   Foundation abide form invalid event handler
--------------------------------------------------------*/
var genericString = "Error: Please check the required fields and try again.";
var successString = "success: Update successful"
$(document).on("forminvalid.zf.abide", function(ev,frm) {
    //console.log("Form invalid: ", frm);

	/* If we don't wish to run this check add no-validate class to form */
    if ( $(frm).hasClass("no-validate") ) {
        return;
    }

    if ( $(document).find("#response").html().trim() == '<p><i class="fi-alert"></i>  </p>' ) {

        /* Edge case for product insert setp 2: */
        if ( document.location.pathname == "/administration/product_insert.html" )  {

            $(document).find("#response").remove();
            $(document).find("#response").remove();
            $("#response").remove();
            $("#response").remove();

            $("#insert_step_two").find(".row.small-collapse").append('<div class="columns small-12"><div id="response" data-abide-error="" class="alert callout"><p><i class="fi-alert"></i> Please check the required fields and try again. </p></div></div>');

            /* Scroll to top */
        	$('html, body').animate({
        		scrollTop: $("#response").closest(".row").offset().top
        	}, 'normal');

        } else {
            /* Simply Add generic warning message to the response field */
            $("#response").find('p').append(genericString);
        }

    } else {
        /* Add generic warning message to the response field */
        $(document).find("#response").removeAttr("class").addClass("alert callout").html('<p><i class="fi-alert"></i>'+genericString+'</p>').removeAttr("style");
    }

	/* Scroll to top */
	$('html, body').animate({
		scrollTop: $("#response").closest(".row").offset().top
	}, 'normal');

/*--------------------------------------------------------
#   Foundation abide form valid event handler
--------------------------------------------------------*/
}).on("formvalid.zf.abide", function(ev,frm) {
	//console.log("Form valid: " + frm);
});

/* set variables */
var formResponse,
formResponded = false;

/* Response logic */
formResponse = function() {
	// select the target node
	var target = document.querySelector('#response');
	var partOne = $(target).text().split(":");

	if ( !formResponded ) {
		formResponded = true;
		var response =  $(target).text().replace( partOne[0], "").substr(1);

		/* Check response message type */
		switch( partOne[0].toLowerCase().replace(/\s/g, '') ) {
			case "success":
				$(target).html(response).removeAttr("class").addClass("success callout").removeAttr("style");
				break;
			case "error":
				$(target).html(response).removeAttr("class").addClass("alert callout").removeAttr("style");
				break;
			case "warning":
				$(target).html(response).removeAttr("class").addClass("warning callout").removeAttr("style");
				break;
		}

		/* After cool down allow callback again this is to avoice getting in
		*   an infinate loop which is possible with the deprecated MutationEvents api
		*/
		setTimeout( function(){
			formResponded = false;
		}, 500);

	}


}

// Make the return button submit forms
$(document).ready(function() {

    $('.input').keypress(function (e) {
        if (e.which == 13) {
            $('form').submit();
            return false;
        }
    });

    /*  On load ensure the wait spinner is not displayed */
    $('form').find("#waitSpinner").removeClass("fixed");

    /* On waitSpinner click prevent other buttons being clicked */
    $(document).on("click", "#waitSpinner", function(event){
        event.stopPropagation();
        event.preventDefault();
    });

	/*  On form submit event display wait spinner and hide again
    *   hide again after 1 second.
    */
	$('form').on( "submit", function( event ){

        var $this = $(this);

        if ( $this.find("#waitSpinner").length > 0 ) {
            $this.find("#waitSpinner").addClass("fixed");
        } else if ( $("#waitSpinner").length > 0 ) {
            $("#waitSpinner").addClass("fixed");
        }

        /* If form submit failed show short wait spinner */
        if ( $("form").find(".is-invalid-input").length > 0 ) {

            setTimeout( function(){
                $this.find("#waitSpinner").removeClass("fixed");
            }, 400);

        } else if ( $(document).find(".is-invalid-input").length > 0 ) {
            setTimeout( function(){
                $("#waitSpinner").removeClass("fixed");
            }, 400);
        }

        /*  After 5 seconds if form is invalid and wait spinner
        *   still spinning we turn it off.
        */
		setTimeout( function(){

			if ( $(document).find(".is-invalid-input").length > 0 && $("#waitSpinner").hasClass("fixed") ) {
				$("#waitSpinner").removeClass("fixed");
			}

		}, 1200);

	})

    /* Onload check if #response if different */
    if ( $(document).find("#response").html().trim() != '<p><i class="fi-alert"></i>  </p>' ) {
        formResponse();
    }

    /*--------------------------------------------------------
    #   Bind event listener to #Response changes from php
    --------------------------------------------------------*/
    /*  Onload detect if we can use the newer MutationObserver api
    *   to detect if the response field is changed by php
    *   or we use the now deprecated MutationEvents api
    *   NOTE: we need to begin out response messages with sucess:, error: or warning:
    *   in order for this function to work.
    */
    if ( window.MutationObserver && $("#response").length > 0 ) {
        var target = document.querySelector('#response');

        // create an observer instance
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation, index) {
                //console.log(mutation.type);
				if ( index == 0 ) {
                	formResponse();
				}
            });
        });

        // configuration of the observer:
        var config = { attributes: true, childList: true, characterData: true }

        // pass in the target node, as well as the observer options
        observer.observe(target, config);

	/*  Else MutationObserver not supported user the older
    *   MutationEvents api used for IE 9 & 10
    */
    } else if ( window.MutationObserver == undefined  ||  window.MutationObserver == 'undefined' && $("#response").length > 0)  {
        //console.log( "DOMSubtreeModified Supported" );
        $("#response").on("DOMSubtreeModified",function(){
            formResponse();
        });
    }




});
