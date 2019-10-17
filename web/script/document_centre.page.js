/*
  Theme Name: Raremedia PHP core_boilerplate
  Author: Lucas Jordan
  Description: Raremedia PHP core_boilerplate social index page javascript
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

  $(document).foundation();

  /* Instantiate Navigation Bar */
  var navBar = new Navigation({
      target: $("#main-bar"),
      navigationStyleMedium: "accordion",
      navigationStyleSmall: "accordion",
      dropDownTransitionType: "slideDown",
      dropDownTransitionDuration: 0.7,
      dropDownTransitionEase: "softEaseOut",
			hamburgerTransitionDuration: 0.8,
      navigationFixed: "true",
      activeItem: 2
  })

  TweenMax.to( $(".bg-yello-triangle-wrapper"), 0.6, { autoAlpha: 1, ease: Power2.easeOut });
  TweenMax.to( $(".bg-texture"), 0.6, { autoAlpha: 1, ease: Power2.easeOut });

});
