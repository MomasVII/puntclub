/*
  Theme Name: Raremedia Slider Demo
  Author: Lucas Jordan
  Description: Raremedia Carousel Script
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Carousel Documentation
#   Carousel Constructor
#   Auto Play carousel
--------------------------------------------------------------*/


/*--------------------------------------------------------------
#   Carousel Documentation
----------------------------------------------------------------
target:                         // STRING Parent element for the carousel
carouselType: "carousel",       // STRING Type of Carousel | options | carousel (default)
autoPlay: true,                 // BOOLEAN Carousel auto play option
numberOfSlides: 7,              // INTEGER Number of slides in carousel
width: 230,                     // INTEGER width of each carousel item in large view port
widthMedium: 200,               // INTEGER width of each carousel item in medium view port
widthSmall: 150,                // INTEGER width of each carousel item in small view port
marginWidth: 30,                // INTEGER carousel item margin width in large view port
marginWidthMedium: 30,          // INTEGER carousel item margin width in medium view port
marginWidthSmall: 15,           // INTEGER carousel item margin width in small view port
duration: 3                     // INTEGER Transition time for one iteration of the carousl
--------------------------------------------------------------*/


/*--------------------------------------------------------------
#   Carousel Constructor
--------------------------------------------------------------*/
function Carousel ( options ) {

    /* If no target is passed we simply return */
    if( typeof options.target === 'undefined' ) {
        return
    }

    /* Check to ensure new keyword was used to make sure obect is instance of Carousel */
    if ( !( this instanceof Carousel ) ) {
        return new Carousel( options )
    }

    /* Set the slider parameters */
    this.target = ( typeof options.target !== 'undefined' ) ?  options.target : 1
    this.carouselType = ( typeof options.carouselType !== 'undefined' ) ?  options.carouselType : 'carousel'
    this.autoPlay = ( typeof options.autoPlay !== 'undefined' ) ?  options.autoPlay : true
    this.numberOfSlides = ( typeof options.numberOfSlides !== 'undefined' ) ?  options.numberOfSlides : 1
    this.width = ( typeof options.width !== 'undefined' ) ?  options.width : 200
    this.widthMedium = ( typeof options.widthMedium !== 'undefined' ) ?  options.widthMedium : 180
    this.widthSmall = ( typeof options.widthSmall !== 'undefined' ) ?  options.widthSmall : 120
    this.marginWidth = ( typeof options.marginWidth !== 'undefined' ) ?  options.marginWidth : 20
    this.marginWidthMedium = ( typeof options.marginWidthMedium !== 'undefined' ) ?  options.marginWidthMedium : 18
    this.marginWidthSmall = ( typeof options.marginWidthSmall !== 'undefined' ) ?  options.marginWidthSmall : 15
    this.duration = ( typeof options.duration !== 'undefined' ) ?  options.duration : 3

    /* Auto play carousel */
    this.autoPlayCarousel()

    /*--------------------------------------------------------------
    #   Resize event
    --------------------------------------------------------------*/
    if ( typeof resizeByHandler !== 'undefined' ) {
        $(window).on("resizeByHandler", function () {
            this.autoPlayCarousel()
        }.bind(this))
    } else {
        $(window).on("resize", function () {
            this.autoPlayCarousel()
        }.bind(this))
    }

}


/*--------------------------------------------------------------
#   Auto Play carousel
--------------------------------------------------------------*/
Carousel.prototype.autoPlayCarousel = function() {
    /* If not autoPlay = true then return */
    if ( !this.autoPlay ) {
        return
    }
    var element = this.target
    var maxHeight = 0
    var animation = null

    /* Clean up carousel */
    TweenMax.killTweensOf( $( element ).find( ".slide" ) ); // Stop Tweens
    $( element ).removeAttr("style");
    $( element ).find( ".inner" ).removeAttr("style");
    $( element ).find( ".inner .slide" ).removeAttr("style");

    /* Step 1: work out how many logo's we can realistically display at once in this view port */

    /* Set Variables */
    var container = $( element ).find( ".inner" ),
    slides = $( element ).find( ".slides" ),
    maxWidth = parseInt( parseInt( $( element ).css("width").substr( $( element ).css("width") - 2 )) ),
    numberOfSlides = this.numberOfSlides,
    itemWidth = this.width,
    duration = this.duration,
    gutterWidth = this.marginWidth

    /* Determine what our current view port is */
    /* If project is using Zurb Foundation our job is easy  */
    if ( typeof Foundation.MediaQuery.current !== 'undefined' ) {

        if ( Foundation.MediaQuery.current == "medium" ){

            itemWidth = this.widthMedium
            gutterWidth = this.marginWidthMedium

        } else if ( Foundation.MediaQuery.current == "small" ){
            itemWidth = this.widthSmall
            gutterWidth = this.marginWidthSmall
        }

    } else {

      /* NOTE  Foundation.MediaQuery.medium = 40em or 640px */
      /* NOTE  Foundation.MediaQuery.large = 64em or 1024px */
      /* By default use smallest dementions mobile first responsive */

      itemWidth = this.widthSmall
      gutterWidth = this.marginWidthSmall

      if ( $(window).innerWidth() > 639 ) {
        itemWidth = this.widthMedium
        gutterWidth = this.marginWidthMedium
      } else if ( $(window).innerWidth() > 1023 ){
        itemWidth = this.width
        gutterWidth = this.marginWidth
      } else {
        itemWidth = this.widthSmall
        gutterWidth = this.marginWidthSmall
      }

    }


    var itemOffset = itemWidth + ( gutterWidth * 2 )
    var $slides = $( element ).find(".inner .slide")

    /* Apply dynamic styles */
    $( container ).find(".slide").css("padding", "0 "+gutterWidth+"px")

    /* Apply the item size to display the desired number of slides in current view port and */
    $( container ).find(".slide").css("width", itemWidth+"px" )
    maxWidth = itemOffset * ( numberOfSlides )

    $( slides ).css("width", maxWidth+"px" )
    $( slides ).css("left", -itemOffset+"px")

    /* calculate carousel dynamic height */
    $( element ).find( ".inner > .slides > .slide" ).each( function( index ){
        if( $( this ).children().height() > maxHeight ){
            maxHeight = $( this ).children().height()
        }
    });

    /* Update dynamic height */
    $( element ).find( ".inner" ).css({ "height": maxHeight+"px" })
    $( $slides ).css({ "height": maxHeight+"px" })

    /* Step 2: animate */
    TweenMax.set($slides, {
        autoAlpha: 1,
        x:function(i) {
            return i * itemOffset;
        },
        force3D: false,
        transformOrigin: "0% 0%"
    });

    animation = TweenMax.to( $slides, ( duration * numberOfSlides ), {
      paused: true,
      ease: Power0.easeNone,
      x: "+="+maxWidth,
      modifiers: {
        x: function(x) {
          return x % maxWidth
        }
      },
      repeat: -1,
      onReverseComplete:function() {
        this.progress(1).reverse();
      }
    });

    animation.progress(1).reverse();

}
