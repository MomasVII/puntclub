/*
  Theme Name: Raremedia PHP core_boilerplate
  Author: Lucas Jordan
  Description: Raremedia Rotator Script
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Rotator Documentation
#   Rotator Constructor
#   Rotator play function
#   Rotator pause function
--------------------------------------------------------------*/

/*--------------------------------------------------------------
#   Rotator Documentation
----------------------------------------------------------------
target = null,                      // STRING parent element for the rotator
numberOfImages = 1                  // INTEGER Number of images in this rotator
autoPlay = true                     // BOOLEAN whether we play rotator on load or not
stayTime = 5                        // INTEGER durtation each image is displayed
transitionTime = 3                  // INTEGER duration tweening between images
transitionEase = "Power0.easeNone"  // STRING gsap easing to use in the transition
orderRandom = false                 // BOOLEAN whether display images in source order or random order
autoHeight = false                  // BOOLEAN if set to true we don't set height on the rotator root element.
rotatorPlaying = false              // FLAG used to handle the play pause logic of the rotator
currentView = 0                     // INTEGER property to store the currnt visible rotator view for this instance
nextView = null                     // this is the variable nave to store the recursive rotation tweens declared here so we may play and pause tween
--------------------------------------------------------------*/


/*--------------------------------------------------------------
#   Rotator Constructor
--------------------------------------------------------------*/
function Rotator ( options ) {

    /* If no target is passed we simply return */
    if( typeof options.target === 'undefined' ) {
        return
    }

    /* Check to ensure new keyword was used to make sure obect is instance of Rotator */
    if ( !( this instanceof Rotator ) ) {
        return new Rotator( options )
    }

    /* Set the rotator parameters */
    this.target = ( typeof options.target !== 'undefined' ) ?  options.target : 1
    this.numberOfImages = ( typeof options.numberOfImages !== 'undefined' ) ?  options.numberOfImages : 1
    this.autoPlay = ( typeof options.autoPlay !== 'undefined' ) ?  options.autoPlay : true
    this.stayTime = ( typeof options.stayTime !== 'undefined' ) ?  options.stayTime : 3
    this.transitionTime = ( typeof options.transitionTime !== 'undefined' ) ?  options.transitionTime : 1.3
    this.transitionEase = ( typeof options.transitionEase !== 'undefined' ) ?  options.transitionEase : "Power0.easeNone"
    this.orderRandom = ( typeof options.orderRandom !== 'undefined' ) ?  options.orderRandom : false
    this.autoHeight = ( typeof options.autoHeight !== 'undefined' ) ?  options.autoHeight : false
    this.rotatorPlaying = false
    this.currentView = 0
    this.nextView = null

    /* Set the rotator Dimentions*/
    this.setDimensions()

    /* Call Auto Play */
    if ( this.autoPlay ) {
        this.playFunction()
    }

    /* NOTE temporary just an example of play / pause functionality */
    this.target.find(".btnPause").click( function(){
        this.pauseFunction()
    }.bind(this))

    this.target.find(".btnPlay").click( function(){
        this.playFunction()
    }.bind(this))

    /*--------------------------------------------------------------
    #   Resize event
    --------------------------------------------------------------*/
    $(window).on("resizeByHandler", function () {
        this.setDimensions()
    }.bind(this))

}


/*--------------------------------------------------------------
#   Slider Set Dimentions Function
--------------------------------------------------------------*/
Rotator.prototype.setDimensions = function() {

    /* If we set autoHeight to true we don't set the height of root element */
    if ( this.autoHeight ) {
        return
    }

    /* Create temporary image gather dimentions */
    /*set the height on the .wrapper then remove the image again */
    var maxHeight = 0
    var element = this.target

    var img = new Image()
    var src = element.find(".slide img").attr("src")
    img.src = src

    var ratio = $( element ).width() / img.width
    maxHeight = img.height * ratio
    img = null

    $( element ).find( ".wrapper" ).css( "height", maxHeight+"px" )
}


/*--------------------------------------------------------------
#   Rotator play function
--------------------------------------------------------------*/
Rotator.prototype.playFunction = function() {

    /* If already playing return */
    if ( this.rotatorPlaying == true ) {
        return
    }

    /* If resume ensure all tweens have been killed */
    if ( this.nextView != null ) {
        TweenMax.killDelayedCallsTo(this.nextView);
    }

    this.rotatorPlaying = true;

    /* Set all slides after first slide alpha of 0 */
	TweenMax.set(this.target.find(".slide").filter(":gt(0)"), { autoAlpha: 0 });

    this.nextView = function()  {

        /* Fade out current slide */
        TweenMax.to( this.target.find(".slide").eq(this.currentView), this.transitionTime, { autoAlpha: 0, ease: this.transitionEase } )

        // Update the iterator
        if ( !this.orderRandom ) {

            if ( this.numberOfImages == this.target.find(".slide").length ) {
                this.currentView = ++(this.currentView) % this.target.find(".slide").length
            } else {

                if ( this.currentView == ( this.numberOfImages - 1 ) ) {
                    this.currentView = 0
                } else {
                    this.currentView = ++(this.currentView) % this.target.find(".slide").length
                }

            }

        /* If in random order */
        } else if ( this.orderRandom ) {

            var tempNumber = _.random( 0, this.numberOfImages )

            if ( tempNumber !=  this.currentView ) {
                this.currentView = tempNumber
            } else {
                while ( tempNumber == this.currentView ) {
                    tempNumber = _.random( 1, this.numberOfImages )
                }
                this.currentView = tempNumber
            }
        }

        /* Fade in new current slide */
        TweenMax.to( this.target.find(".slide").eq(this.currentView), this.transitionTime, { autoAlpha: 1, ease: this.transitionEase } )

        // Recursive call back
        TweenMax.delayedCall(this.stayTime, this.nextView)

	}.bind(this);

    /* Call recursive rotator function */
	TweenMax.delayedCall(this.stayTime, this.nextView );

}

/*--------------------------------------------------------------
#   Rotator pause function
--------------------------------------------------------------*/
Rotator.prototype.pauseFunction = function() {

    if ( this.rotatorPlaying == false ) {
        return
    }
    this.rotatorPlaying = false
    TweenMax.killDelayedCallsTo( this.nextView );

}
