/*
  Theme Name: Raremedia PHP core_boilerplate
  Author: Lucas Jordan
  Description: Raremedia Slider Script
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Slider Documentation
#   Slider Constructor
#   Slider Set Dimentions
#   Slider Goto Slide Function
#   Slider Render Arrow Function
#   Slider Render Nav Function
#   Slider bind draggable
#   Slider Auto Play function
--------------------------------------------------------------*/

/*--------------------------------------------------------------
#   Slider Documentation
----------------------------------------------------------------
var taget = null,                // STRING Parent element for the slider
sliderType = "slider-content",   // STRING Slider Type | options | SLider - slider-banner 
numberOfSlides = 1,              // INTEGER Number of slide in Slider
showControls = true,             // BOOLEAN Global option to show slider controls
showArrows = true,               // BOOLEAN show left/ right arrow control
arrowsPosition = "pos-hoz",      // STRING Arrow position | options | pos-hoz - horizonal (default), pos-vert vertical
showNav = true,                  // BOOLEAN show/ hide the slider nav controls
navType = "bullets",             // STRING Type of controls to show | options | bullets, numbers
navPosition = "bottom",          // STRING Control Position | Options | bottomCenter, bottomLeft, bottomRight, topCnter, topLeft, topRight
showProgressBar = false,         // BOOLEAN SHow Progress Bar
progressBarPosition = "top",     // STRING Progress Bar Position | Options | bottomCenter, bottomLeft, bottomRight, topCnter, topLeft, topRight
easing = "sf-lr",                // STRING Easing Short Fade: left to right (sf-lr), Short Fade: right to left (sf-rl), Short Fade: bottom to top (sf-bt), Short Fade: top to bottom (sf-tb), Fade (fade)
transitionTime = 1,              // INTEGER Transition time between slides
stayTime = 5,                    // INTEGER Stay time, time spent on each slide
transitionType = "slide",        // STRING autoPlay transition type
contentType = "content",         // STRING slide inner content type
contentTransitionType = "none",  // STRING slide inner content transition type | Options | slide-lr, slide-rl, slide-bt, slide-tb, fade, none (default)
contentPosition = "full",        // STRING Slide content position | Options | .pos-tl .pos-tc .pos-tr .pos-bl .pos-bc .pos-br .pos-mr .pos-ml
holdOnHover = false,             // BOOLEAN stay on current slide on mouse hover
touchControl = false,            // BOOLEAN slide swipe controls
autoPlay = false,                // BOOLEAN slider auto play option
autoPlayReverse = false          // BOOLEAN Slider reverse direction
autoHeight = false               // BOOLEAN slider auto height option
maxHeight = 650                  // INTEGER Max height for slider type banner large view ports
maxHeightMedium = 450            // INTEGER Max height for slider type banner Medium view ports
maxHeightSmall = 400             // INTEGER Max height for slider type banner Small view ports
--------------------------------------------------------------*/


/*--------------------------------------------------------------
#   Slider Constructor
--------------------------------------------------------------*/
function Slider ( options ) {

    /* If no target is passed we simply return */
    if( typeof options.target === 'undefined' ) {
        return
    }

    /* Check to ensure new keyword was used to make sure obect is instance of Slider */
    if ( !( this instanceof Slider ) ) {
        return new Slider( options )
    }

    /* Set the slider parameters */
    this.target = ( typeof options.target !== 'undefined' ) ?  options.target : 1
    this.sliderType = ( typeof options.sliderType !== 'undefined' ) ?  options.sliderType : 'slider-content'
    this.numberOfSlides = ( typeof options.numberOfSlides !== 'undefined' ) ?  options.numberOfSlides : 1
    this.showControls = ( typeof options.showControls !== 'undefined' ) ?  options.showControls : true
    this.showArrows = ( typeof options.showArrows !== 'undefined' ) ?  options.showArrows : true
    this.arrowsPosition = ( typeof options.arrowsPosition !== 'undefined' ) ?  options.arrowsPosition : 'pos-hoz'
    this.showNav = ( typeof options.showNav !== 'undefined' ) ?  options.showNav : true
    this.navType = ( typeof options.navType !== 'undefined' ) ?  options.navType : 'bullets'
    this.navPosition = ( typeof options.navPosition !== 'undefined' ) ?  options.navPosition : 'bottom'
    this.showProgressBar = ( typeof options.showProgressBar !== 'undefined' ) ?  options.showProgressBar : false
    this.progressBarPosition = (typeof options.progressBarPosition !== 'undefined' ) ?  options.progressBarPosition : 'top'
    this.easing = ( typeof options.easing !== 'undefined' ) ?  options.easing : 'sf-lr'
    this.transitionTime = ( typeof options.transitionTime !== 'undefined' ) ?  options.transitionTime : 1
    this.stayTime = ( typeof options.stayTime !== 'undefined' ) ?  options.stayTime : 5
    this.transitionType = ( typeof options.transitionType !== 'undefined' ) ?  options.transitionType : 'slide'
    this.contentType = ( typeof options.contentType !== 'undefined' ) ?  options.contentType : 'content'
    this.contentTransitionType = ( typeof options.contentTransitionType !== 'undefined' ) ?  options.contentTransitionType : 'none'
    this.contentPosition = ( typeof options.contentPosition !== 'undefined' ) ?  options.contentPosition : 'full'
    this.holdOnHover = ( typeof options.holdOnHover !== 'undefined' ) ?  options.holdOnHover : false
    this.touchControl = ( typeof options.touchControl !== 'undefined' ) ?  options.touchControl : false
    this.autoPlay = ( typeof options.autoPlay !== 'undefined' ) ?  options.autoPlay : false
    this.autoPlayReverse = ( typeof options.autoPlayReverse !== 'undefined' ) ?  options.autoPlayReverse : false
    this.autoHeight = ( typeof options.autoHeight !== 'undefined' ) ?  options.autoHeight : false
    this.maxHeight = ( typeof options.maxHeight !== 'undefined' ) ?  options.maxHeight : 650
    this.maxHeightMedium = ( typeof options.maxHeightMedium !== 'undefined' ) ?  options.maxHeight : 450
    this.maxHeightSmall = ( typeof options.maxHeightSmall !== 'undefined' ) ?  options.maxHeight : 400

    /* Set the slider Dimentions*/
    setTimeout(function(){
        this.setDimensions()
    }.bind(this), 50);

    /* Define debounced goto event */
    this.gotoHandler  = _.debounce( function( action, index, target){
        this.gotoSlide( action, index, target )
    }.bind(this), 1000, { leading:true, trailing:false })

    /* Render Slider Arrow Controls */
    var arrowControls = this.renderArrows()
    $(this.target).find(" > .inner").append(arrowControls)

    /* Render Nav controls */
    var navControls = this.renderNav()
    $(this.target).find(" > .inner").append(navControls)

    /* Bind draggable touch control */
    this.bindDraggable()

    /* Auto play auto play sliders */
    this.autoPlaySlider()


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
Slider.prototype.setDimensions = function() {
    var maxHeight = 0
    var element = this.target
    var maxContainerHeight = this.maxHeight

    if ( typeof Foundation.MediaQuery.current !== 'undefined' ) {

        switch (  Foundation.MediaQuery.current ) {
            case "large":
                maxContainerHeight = this.maxHeight
                break
            case "medium":
                maxContainerHeight = this.maxHeightMedium
                break
            case "small":
                maxContainerHeight = this.maxHeightSmall
                break
            default:
                maxContainerHeight = this.maxHeight
                break
        }
    }


    $( element ).removeAttr("style")
    $( element ).find( ".slides" ).removeAttr("style")

    if (  $( element ).data("slider-type") === "slider-content" ) {
        $( element ).find( ".slides > .slide" ).each( function( index ) {

            if ( $(this).find(".s-content").height() > maxHeight ){
                maxHeight = $(this).find(".s-content").height()
            }
        })
    } else if (  $( element ).data("slider-type") === "slider-banner" )  {

        $( element ).find( ".slides > .slide" ).each( function( index ) {

            var img = new Image()
            var src = $(this).find(".s-bg > img").attr("src")
            img.src = src

            var ratio = $( element ).width() / img.width
            maxHeight = img.height * ratio
            img = null

        })
    }

    if ( $( element ).find(".s-nav").length > 0 ) {
        maxHeight = maxHeight + $( element ).find(".s-nav").height();
    }

    /* IF we are a banner slider we limit the height per max height parameter maxHeight or maxHeightMedium or maxHeightSmall */
    if ( maxHeight > maxContainerHeight &&  $( element ).data("slider-type") === "slider-banner"  ) {
        $( element ).find( ".slides" ).css( "height", maxContainerHeight+"px" )
    } else {
        $( element ).find( ".slides" ).css( "height", maxHeight+"px" )
    }

}


/*--------------------------------------------------------------
#   Slider Goto Slide Function
--------------------------------------------------------------*/
Slider.prototype.gotoSlide = function( action, index, target, thisTransitionTime ) {
    var parent = ''
    var direction = ''

    /* Set variables */
    var target = "#"+target
    var thisSlide = $(target).find('.slide[data-slide-active="true"]'),
    slideNumber = $(target).find('.slide[data-slide-active="true"]').data("slide-number"),
    duration = this.transitionTime,
    slideWidth = $(target).width(),
    slideHeight = $( target ).height()

    /* if this.transitionTime == underfined because of scope we get the passed transition time */
    if ( typeof duration === 'undefined' && typeof thisTransitionTime !== 'undefined' ) {
        duration = thisTransitionTime
    } else {
        duration = 1
    }

    var currentSlide = ''
    var nextSlide = ''

    /* Post Tween Cleaup up function */
    function slideCleanup(){

        $(target).find('.slide[data-slide-active="true"]').attr("data-slide-active", "false")
        $( nextSlide ).attr("data-slide-active", "true")

        TweenMax.set( currentSlide, { autoAlpha: 0 } )
        TweenMax.set( nextSlide, { autoAlpha: 1 } )
        index = 0
    }

    /* Slide to next slide animation */
    function gotoNext() {
        TweenMax.fromTo( currentSlide, duration, { x: 0, autoAlpha: 1 }, { x: -slideWidth, ease: Power2.easeOut } )
        TweenMax.fromTo( nextSlide, duration, { x: slideWidth, autoAlpha: 1 }, { x: 0, ease: Power2.easeOut, onComplete: slideCleanup } )
    }
    /* If we parsed differnt easings we goto next slide here */
    function differentEaseNext() {
      TweenMax.fromTo( currentSlide, duration, { x: 0, autoAlpha: 1 }, { x: -slideWidth, ease: Power2.easeInOut } )
      TweenMax.fromTo( nextSlide, duration, { x: slideWidth, autoAlpha: 1 }, { x: 0, ease: Power1.easeOut, onComplete: slideCleanup } )
    }
    /* If we parsed vertical easing we go to next bottom to top here */
    function gotoVerticalNext() {
        TweenMax.fromTo( currentSlide, duration, { y: 0, autoAlpha: 1 }, { y: -slideHeight, ease: Power2.easeOut } )
        TweenMax.fromTo( nextSlide, duration, { y: slideHeight, autoAlpha: 1 }, { y: 0, ease: Power2.easeOut, onComplete: slideCleanup } )
    }
    /* Slide to previous slide animation */
    function gotoPrevious() {
      TweenMax.fromTo( currentSlide, duration, { x: 0, autoAlpha: 1 }, { x: slideWidth, ease: Power2.easeOut } )
      TweenMax.fromTo( nextSlide, duration, { x: -slideWidth, autoAlpha: 1 }, { x: 0, ease: Power2.easeOut, onComplete: slideCleanup } )
    }
    /* If we parsed differnt easings we goto Previous slide here */
    function differentEasePrevious() {
      TweenMax.fromTo( currentSlide, duration, { x: 0, autoAlpha: 1 }, { x: slideWidth, ease: Power2.easeInOut } )
      TweenMax.fromTo( nextSlide, duration, { x: -slideWidth, autoAlpha: 1 }, { x: 0, ease: Power1.easeOut, onComplete: slideCleanup } )
    }
    /* If we parsed vertical easing we go to previous top to bottom here */
    function gotoVerticalPrevious() {
        TweenMax.fromTo( currentSlide, duration, { y: 0, autoAlpha: 1 }, { y: slideHeight, ease: Power2.easeOut } )
        TweenMax.fromTo( nextSlide, duration, { y: -slideHeight, autoAlpha: 1 }, { y: 0, ease: Power2.easeOut, onComplete: slideCleanup } )
    }
    // If we parsed fade easing we simply fade out currentslide and fade in nextSlide
    function gotoFade(){
        TweenMax.fromTo( currentSlide, duration, { autoAlpha: 1 }, { autoAlpha: 0, ease: Power2.easeOut } )
        TweenMax.fromTo( nextSlide, duration, { autoAlpha: 0 }, { autoAlpha: 1, ease: Power2.easeOut, onComplete: slideCleanup } )
    }
    /* If we parsed an index value we determine which way to scroll the slider */
    if( index !== undefined && index != 0 ){
        if( index > slideNumber ){
            currentSlide = thisSlide
            nextSlide = $(target).find(".slides [data-slide-number='"+ index + "']")

            /* Update s-nav */
            $( target).find(".s-nav li[data-control-active='true']").attr("data-control-active", "false")
            $( target).find(".s-nav li:nth-of-type("+( index )+")").attr("data-control-active", "true")
            direction = "forward"
        }
        else if( index < slideNumber ){
            currentSlide = thisSlide
            nextSlide = $(target).find(".slides [data-slide-number='" + index + "']")

            /* Update s-nav */
            $( target).find(".s-nav li[data-control-active='true']").attr("data-control-active", "false")
            $( target).find( ".s-nav li:nth-of-type("+index+")").attr("data-control-active", "true")
            direction = "backwards"
        }
    }
    else { // Else We Parsed an Action
        if( action == "next" ){
            if (thisSlide != '' && slideNumber < $(target).find(".slides .slide").length) {
                currentSlide = thisSlide
                nextSlide = $(target).find(".slides [data-slide-number='" + ( parseInt(slideNumber) + 1 ) + "']")

                /* Update s-nav */
                $( target ).find(".s-nav li[data-control-active='true']").attr("data-control-active", "false")
                $( target ).find(".s-nav li:nth-of-type("+( parseInt(slideNumber) + 1 )+")").attr("data-control-active", "true")
            }
            else if (thisSlide != '' && slideNumber == $(target).find(".slides .slide").length) {
                currentSlide = $(target).find(".slides [data-slide-number='" + parseInt($(target+" .slides .slide").length) + "']" )
                nextSlide = $(target).find(".slides [data-slide-number='1']")

                /* Update s-nav */
                $( target ).find(".s-nav li[data-control-active='true']").attr("data-control-active", "false")
                $( target ).find(".s-nav li:nth-of-type(1)").attr("data-control-active", "true")
            }
            direction = "forward"
        }
        else if( action == "previous" ){
            /* Slide to previous slide */
            if( thisSlide != '' && slideNumber > 1 ){

                currentSlide = thisSlide
                nextSlide = $(target).find(".slides [data-slide-number='" + ( parseInt( slideNumber ) - 1 ) + "']")

                /* Update s-nav */
                $( target ).find(".s-nav li[data-control-active='true']").attr("data-control-active", "false")
                $( target ).find(".s-nav li:nth-of-type("+( parseInt(slideNumber) - 1 )+")").attr("data-control-active", "true")
            }
            /* We've reached the first slide set up logic for loop */
            else if( thisSlide != '' && slideNumber == 1 ){
                currentSlide = thisSlide
                nextSlide = $(target).find(".slides [data-slide-number='" +$(target+" .slides .slide").length + "']")

                /* Update s-nav */
                $( target ).find(".s-nav li[data-control-active='true']").attr("data-control-active", "false")
                $( target ).find(".s-nav li:nth-of-type("+( $(target+" .slides .slide").length )+")").attr("data-control-active", "true")
            }
            direction = "backwards"
        }
    }
    /* Now we've determined the current slide and next slide we call the correct transition */
    /* TODO: [@Lucas] - Turn this in to a map for effienentcy */
    if ( direction === "forward"){
        switch( $(target).data('easing') ) {
            case "diffEase":
                differentEaseNext()
                break
            case "vertical":
                gotoVerticalNext()
                break
            case "fade":
                gotoFade()
                break
            default:
                gotoNext()
                break
        }
    }
    /* TODO: [@Lucas] - Turn this in to a map for effienentcy */
    else if ( direction === "backwards" ){
        switch( $(target).data('easing') ) {
            case "diffEase":
                differentEasePrevious()
                break
            case "vertical":
                gotoVerticalPrevious()
                break
            case "fade":
                gotoFade()
                break
            default:
                gotoPrevious()
                break
        }
    }
    /* If we passed content transition type */
    if ( this.contentTransitionType != "none" ) {
        var transitionType = this.contentTransitionType
        /* TODO: [@Lucas] - Turn this in to a map for effienentcy */
        switch( transitionType ) {
            case "slide-lr":
                TweenMax.fromTo( $(currentSlide).find(" > div"), duration, { autoAlpha: 1 }, { autoAlpha: 0, ease: Power2.easeOut } )
                TweenMax.fromTo( $(nextSlide).find(" > div"), duration, { autoAlpha: 0, x: -100 }, { delay: duration, x: 0, autoAlpha: 1, ease: Power2.easeOut } )
                break
            case "slide-rl":
                TweenMax.fromTo( $(currentSlide).find(" > div"), duration, { autoAlpha: 1 }, { autoAlpha: 0, ease: Power2.easeOut } )
                TweenMax.fromTo( $(nextSlide).find(" > div"), duration, { autoAlpha: 0, x: 100 }, { delay: duration, x: 0, autoAlpha: 1, ease: Power2.easeOut } )
                break
            case "slide-bt":
                TweenMax.fromTo( $(currentSlide).find(" > div"), duration, { autoAlpha: 1 }, { autoAlpha: 0, ease: Power2.easeOut } )
                TweenMax.fromTo( $(nextSlide).find(" > div"), duration, { autoAlpha: 0, y: 50 }, { delay: duration, y: 0, autoAlpha: 1, ease: Power2.easeOut } )
                break
            case "slide-tb":
                TweenMax.fromTo( $(currentSlide).find("div"), duration, { autoAlpha: 1 }, { autoAlpha: 0, ease: Power2.easeOut } )
                TweenMax.fromTo( $(nextSlide).find("div"), duration, { autoAlpha: 0, y: -50 }, { delay: duration, y: 0, autoAlpha: 1, ease: Power2.easeOut } )
                break
            case "fade":
                TweenMax.fromTo( $(currentSlide).find(" > div"), duration, { autoAlpha: 1 }, { delay: duration, autoAlpha: 0, ease: Power2.easeOut } )
                TweenMax.fromTo( $(nextSlide).find(" > div"), duration, { autoAlpha: 0 }, { delay: duration, autoAlpha: 1, ease: Power2.easeOut } )
                break

        }
    }

    /* Reset the x position of the .slides */
    TweenMax.to( $(target).find(".slides"), this.transitionTime, { x: 0, onComplete: function() { TweenMax.set( $(target).find(".slides"), { clearProps: "x" }) } })
}


/*--------------------------------------------------------------
#   Slider Render Arrows Function
--------------------------------------------------------------*/
Slider.prototype.renderArrows = function() {
    if ( this.showControls && this.showArrows ) {
        var self = this

        var $container = $('<div>')
        var $previousLink = $('<a href="javascript:void(0)" class="s-arrows slide-left"  />')

        $previousLink.click( function () {
            this.gotoHandler("previous", undefined, this.target.substring(1) )
        }.bind(this))

        var $previousArrow = $('<i class="icons icons-arrow left small" />')
        $previousLink.append( $previousArrow )

        var $nextLink  = $('<a href="javascript:void(0)" class="s-arrows slide-right" />')
        $nextLink.click( function () {
            this.gotoHandler("next", undefined, this.target.substring(1) )
        }.bind(this))

        var $nextArrow = $('<i class="icons icons-arrow right small" />')
        $nextLink.append($nextArrow)
        $container.append( $previousLink )
        $container.append( $nextLink )

        return $container

    } else {

        return

    }
}


/*--------------------------------------------------------------
#   Slider Render Nav Function
--------------------------------------------------------------*/
Slider.prototype.renderNav = function() {
    if ( this.showControls && this.showNav ) {

        if ( this.navType == "bullets" ) {
            var $list = $('<ol class="s-nav bullets pos-bm">')
            var $listItem = []

            for (var i = 1; i <= this.numberOfSlides; i++) {
                switch(i) {
                    case 1:
                        $listItem[i] = $('<li data-slide-to="'+i+'" data-control-active="true"></li>')
                        break
                    default:
                        $listItem[i] = $('<li data-slide-to="'+i+'" data-control-active="false"></li>')
                        break
                }
                $listItem[i].click( function (e) {
                    this.gotoHandler(undefined, $(e.target).data("slide-to"), this.target.substring(1) )
                }.bind(this))
                $list.append( $listItem[i] )
            }

        } else if ( this.navType == "numbers" ) {
            var $list = $('<ol class="s-nav numbers pos-br">')
            var $listItem = []
            var $span1 = []
            var $span2 = []
            for (var i = 1; i <= this.numberOfSlides; i++) {
                switch(i) {
                    case 1:
                        $listItem[i] = $('<li data-slide-to="'+i+'" data-control-active="true">')
                        $span1[i] = $('<span data-slide-to="'+i+'">'+i+'</span>')
                        $span2[i] = $('<span><i class="icons icons-arrow right xsmall" /></span>')
                        break
                    case this.numberOfSlides:
                        $listItem[i] = $('<li data-slide-to="'+i+'" data-control-active="false">')
                        $span1[i] = $('<span data-slide-to="'+i+'">'+i+'</span>')
                        $span2[i] = $('')
                        break
                    default:
                        $listItem[i] = $('<li data-slide-to="'+i+'" data-control-active="false">')
                        $span1[i] = $('<span data-slide-to="'+i+'">'+i+'</span>')
                        $span2[i] = $('<span><i class="icons icons-arrow right xsmall" /></span>')
                        break
                }
                $span1[i].click( function (e) {
                    this.gotoHandler(undefined, $(e.target).data("slide-to"), this.target.substring(1) )
                }.bind(this))
                $listItem[i].append($span1[i])
                $listItem[i].append($span2[i])
                $list.append( $listItem[i] )
            }
        }

        return $list

    } else {

        return

    }
}


/*--------------------------------------------------------------
#   Slider Bind Draggable
----------------------------------------------------------------*/
Slider.prototype.bindDraggable = function() {

    if ( !this.touchControl ) {
        return
    }

    var element = this.target
    var thisGoto = this.gotoHandler
    var thisTransitionTime = this.transitionTime

    /* We calculate a slider theshold of 12% of the sliders width in either direction  */
    /* Before we trigger out goTo function */
    /* NOTE We lowwer the threshold required on smaller view ports */
    var thresholdX = $(element).find(".slides").width() * 20 / 100

    if ( $(".slides").width() > 767 && $(".slides").width() < 1025 ){
        thresholdX = $(element).find(".slides").width() * 5 / 100
    } else if ( $(element).find(".slides").width() > 319 && $(element).find(".slides").width() < 767 ){
        /* NOTE Small devices struggle (Found mainly on Android) where by draggable has issue in such*/
        /* Small containers */
        thresholdX = 1
    }
    var startDragX = 0

    var drag = Draggable.create(
        $(element).find(".slides"),
        {
            type: "x",
            zIndexBoost: false,
            cursor: "move",
            onDrag: function(){ evaluateDragX( drag[0].x ) },
            onDragEnd: function(){ resetXPosition( drag[0].x ) },
            bounds:{ minX: -500, maxX: 500, minY:500, maxY: 500 },
            edgeResistance: 0
        }
    )

    function resetXPosition ( dragX ) {
        /* Reset .slides x position */
        if ( $(element).find(".slides").width() > 767 ) {
            if ( dragX < thresholdX ) {
                TweenMax.to( $(element).find(".slides"), 0.5, { x: 0, onComplete: function(){ TweenMax.set( $(element).find(".slides"), { clearProps: "x" })  }})
            }
        }
        return
    }

    /* Function to check whether we have crossed over the swipe threshold */
    function evaluateDragX ( dragX ) {
        var isPositive = false
        var action = undefined

        /* Check if dragX position is in the positive or negative direction */
        if ( dragX > 0 ) {
            isPositive = true
        }
        dragX = Math.abs(dragX)
        if ( dragX >= thresholdX ) {
            // We've reached out theshold !
            if ( isPositive === true ) {
                action = "previous"
            } else {
                action = "next"
            }
            if ( action !== undefined ) {
                /* GOTO next slide temporary disable the draggable object and reenable after 1 second */
                drag[0].disable()
                thisGoto(action, undefined, element.substring(1), thisTransitionTime)
                setTimeout(function(){ drag[0].enable() }, 1000)
            }
        } else {
            return
        }
        return
    }
}


/*--------------------------------------------------------------
#   Slider Auto Play Function
--------------------------------------------------------------*/
Slider.prototype.autoPlaySlider = function() {
    /* If we are not an auto play slider return */
    if ( !this.autoPlay ) {
        return
    }

    var thisGoto = this.gotoHandler
    var thisStayTime = this.stayTime
    var thisTransitionTime = this.transitionTime
    var thisTransitionType = this.transitionType
    var thisID = this.target.substring(1)
    var thisAutoPlayReverse = this.autoPlayReverse

    var nextSlide = function() {
        switch( thisTransitionType ) {
            case "fade":
                break
            case "slide":
                if ( thisAutoPlayReverse ) {
                    thisGoto("previous", undefined, thisID, thisTransitionTime )
                } else {
                    thisGoto("next", undefined, thisID, thisTransitionTime )
                }
                break
        }

        // Recursive call back
        TweenMax.delayedCall(thisStayTime, nextSlide)
    }
    TweenMax.delayedCall(thisStayTime, nextSlide)

    return

}
