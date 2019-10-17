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
#   Homeepage Intro Animation
#   Document Ready Function
--------------------------------------------------------------*/


/*--------------------------------------------------------------
    Homeepage Intro Animation
--------------------------------------------------------------*/
var homeIntroAnimation = function() {

  var $target = $("body #wrapper");
  var yellowTriangle = $target.find(".bg-yello-triangle-wrapper")
  var mesh = $target.find(".bg-texture");
  var bg = $target.find("#section-home > .bg");
  var logo = $target.find(".pos-home .home-logo");

  var cleanup = function( $element ){
    TweenMax.set( $element, { clearProps: "transform" })
  }

  var introTL = new TimelineMax({paused: true});
  introTL.set( mesh, { autoAlpha: 0 })
         .set( bg, { autoAlpha: 0 })
         .set( yellowTriangle, { autoAlpha: 0, x: 100 })
         .set( logo, { autoAlpha: 0, y: 60 })
         .to( mesh, 1.3, { autoAlpha: 1, ease: Power2.easeOut }, 0.2)
         .to( bg, 5.0, { autoAlpha: 1, ease: Power4.easeOut }, 0.2)
         .to( yellowTriangle, 2.5, { autoAlpha: 1, x: 0, ease: Power2.easeOut, onComplete: cleanup, onCompleteParams: [yellowTriangle] }, 1.0)
         .to( logo, 2.5, { autoAlpha: 1, y: 0, ease: Power2.easeOut, onComplete: cleanup, onCompleteParams: [logo] }, 1.0)

 introTL.play(0);
}


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
      navigationFixed: "true"
  })

  homeIntroAnimation();

});
