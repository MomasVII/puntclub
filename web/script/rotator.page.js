/*
  Theme Name: Raremedia PHP core_boilerplate
  Author: Lucas Jordan
  Description: Raremedia PHP core_boilerplate rotator page javascript
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Document Ready Function
--------------------------------------------------------------*/

/*--------------------------------------------------------------
    Document Ready Function
--------------------------------------------------------------*/
$(document).ready( function(){

    /* Instantiate Navigation Bar */
    var navBar = new Navigation({
        target: $("#main-bar"),
        navigationStyleMedium: "accordion",
        navigationStyleSmall: "accordion",
        dropDownTransitionType: "slideDown",
        dropDownTransitionDuration: 0.7,
        dropDownTransitionEase: "softEaseOut"
    })

    /* Instantiate Background Rotator */
    var bgRotator = new Rotator({
        target: $("#rotator1"),
        numberOfImages: 3,
        orderRandom: false
    })

});
