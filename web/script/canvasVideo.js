/*
  Theme Name: Raremedia PHP core_boilerplate
  Author: Lucas Jordan
  Description: Raremedia HTML5 Canvas Video Script
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   HTML5 Canvas Video Documentation
#   HTML5 Canvas Video Constructor
#   HTML5 Canvas Video Build Elements
#   HTML5 Canvas Video Set Dimentions
--------------------------------------------------------------*/


/*--------------------------------------------------------------
#   HTML5 Canvas Video Documentation
----------------------------------------------------------------
var taget = null,                // STRING Parent element for the slider
--------------------------------------------------------------*/


/*--------------------------------------------------------------
#   HTML5 Canvas Video Constructor
--------------------------------------------------------------*/
function CanvasVideo ( options ) {

  /* If no target is passed we simply return */
  if ( typeof options.target === 'undefined' ) {
      return
  }
  /* If no video source is passed we simply return */
  if ( typeof options.source === 'undefined' ) {
      return
  }

  /* Check to ensure new keyword was used to make sure obect is instance of CanvasVideo */
  if ( !( this instanceof CanvasVideo ) ) {
      return new CanvasVideo( options )
  }


  /* Set the slider parameters */
  this.target = ( typeof options.target !== 'undefined' ) ?  options.target : 1
  this.source = ( typeof options.source !== 'undefined' ) ?  options.source : null
  this.showControls = ( typeof options.showControls !== 'underfined' ) ? options.showControls : false
  this.autoPlay = ( typeof options.autoPlay !== 'undefined' ) ? options.autoPlay : false
  //this.sliderType = ( typeof options.sliderType !== 'undefined' ) ?  options.sliderType : 'slider-content'

  this.buildElements()
  this.setDimensions()
}


/*--------------------------------------------------------------
#   HTML5 Canvas Video Build Elements
--------------------------------------------------------------*/
CanvasVideo.prototype.buildElements = function() {

  var element = this.target,
  source = this.source

  var video = $('<video />', {
      id: 'video',
      controls: this.showControls,
      autoplay: this.autoPlay
  });
  video.appendTo($(element));

  if ( typeof source.webm !== 'undefined' ) {}
  if ( typeof source.ogg !== 'undefined' ) {}
  if ( typeof source.mp4 !== 'undefined' ) {

  }

}


/*--------------------------------------------------------------
#   Slider Set Dimentions Function
--------------------------------------------------------------*/
CanvasVideo.prototype.setDimensions = function() {

  var maxHeight = 0
  var element = this.target

  console.log("Logic Works: ", element )
}
