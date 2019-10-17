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

  /* Instantiate Navigation Bar */
  var navBar = new Navigation({
      target: $("#main-bar"),
      navigationStyleMedium: "accordion",
      navigationStyleSmall: "accordion",
      dropDownTransitionType: "slideDown",
      dropDownTransitionDuration: 0.7,
      dropDownTransitionEase: "softEaseOut",
      hamburgerTransitionType: "rccw",
			hamburgerTransitionDuration: 0.8
  })

  /* Instantiate Index Page Banner Slider */
  var bannerSlider = new Slider({
      target: "#sliderBanner",
      sliderType: "slider-banner",
      numberOfSlides: 2,
      stayTime: 5,
      autoPlay: true,
      showControls: false
  })

  var preload;

	function preloader() {
		// Create a new queue.
		preload = new createjs.LoadQueue(false, "../web/image/demo/");

		// Use this instead to favor xhr loading
		//preload = new createjs.LoadQueue(true, "assets/");

		var plugin = {
			getPreloadHandlers: function () {
				return {
					types: ["image"],
					callback: function (item) {
						var id = item.src.toLowerCase().split("/").pop().split(".")[0];
						item.tag = document.getElementById(id);
					}
				};
			}
		}

		preload.installPlugin(plugin);
		preload.loadManifest([
      "demo_slide01.png",
		  "demo_slide02.png",
		  "demo_slide01.png",
		  "demo_slide02.png",
			"demo_slide03.png",
			"demo_slide04.png",
			"demo_slide05.png",
			"demo_slide06.png"
	  ]);
	}

	preloader()

});
