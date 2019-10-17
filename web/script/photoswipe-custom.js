$(document).ready( function(){

    /* Declare photoSwipe function */
    var initPhotoSwipeFromDOM = function(gallerySelector) {

        // parse slide data (url, title, size ...) from DOM elements
        // (children of gallerySelector)
        var parseThumbnailElements = function(el) {
            var thumbElements = el.childNodes,
                numNodes = thumbElements.length,
                items = [],
                figureEl,
                linkEl,
                size,
                item;

            for (var i = 0; i < numNodes; i++) {

                figureEl = thumbElements[i]; // <figure> element

                // include only element nodes
                if(figureEl.nodeType !== 1) {
                    continue;
                }

                linkEl = figureEl.children[0]; // <a> element

                size = linkEl.getAttribute('data-size').split('x');
                sizeSm = linkEl.getAttribute('data-size-sm').split('x');
                //console.log("SIZE: "+ parseInt(sizeSm[1], 10) );

                // create slide object
                item = {
                    mediumImage: {
                        src: linkEl.getAttribute('data-href'),
                        w: parseInt(sizeSm[0], 10),
                        h: parseInt(sizeSm[1], 10)
                    },
                    originalImage: {
                        src: linkEl.getAttribute('href'),
                        w: parseInt(size[0], 10),
                        h: parseInt(size[1], 10)
                    }
                };



                if(figureEl.children.length > 1) {
                    // <figcaption> content
                    item.title = figureEl.children[1].innerHTML;
                }

                if(linkEl.children.length > 0) {
                    // <img> thumbnail element, retrieving thumbnail url
                    item.msrc = linkEl.children[0].getAttribute('src');
                }

                item.el = figureEl; // save link to element for getThumbBoundsFn
                items.push(item);
            }

            return items;
        };

        // find nearest parent element
        var closest = function closest(el, fn) {
            return el && ( fn(el) ? el : closest(el.parentNode, fn) );
        };

        // triggers when user clicks on thumbnail
        var onThumbnailsClick = function(e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : e.returnValue = false;

            var eTarget = e.target || e.srcElement;

            /* find root element of slide NOTE: We specify the type of element we are looking for HERE! */
            var clickedListItem = closest(eTarget, function(el) {
                return (el.tagName && el.tagName.toUpperCase() === 'DIV');
            });

            if(!clickedListItem) {
                return;
            }
            // find index of clicked item by looping through all child nodes
            // alternatively, you may define index via data- attribute
            var clickedGallery = clickedListItem.parentNode,
                childNodes = clickedListItem.parentNode.childNodes,
                numChildNodes = childNodes.length,
                nodeIndex = 0,
                index;

            for (var i = 0; i < numChildNodes; i++) {
                if(childNodes[i].nodeType !== 1) {
                    continue;
                }

                if(childNodes[i] === clickedListItem) {
                    index = nodeIndex;
                    break;
                }
                nodeIndex++;
            }

            if(index >= 0) {
                // open PhotoSwipe if valid index found
                openPhotoSwipe( index, clickedGallery );
            }
            return false;
        };

        // parse picture index and gallery index from URL (#&pid=1&gid=2)
        var photoswipeParseHash = function() {
            var hash = window.location.hash.substring(1),
            params = {};

            if(hash.length < 5) {
                return params;
            }

            var vars = hash.split('&');
            for (var i = 0; i < vars.length; i++) {
                if(!vars[i]) {
                    continue;
                }
                var pair = vars[i].split('=');
                if(pair.length < 2) {
                    continue;
                }
                params[pair[0]] = pair[1];
            }

            if(params.gid) {
                params.gid = parseInt(params.gid, 10);
            }

            return params;
        };

        var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
            var pswpElement = document.querySelectorAll('.pswp')[0],
                gallery,
                options,
                items;

            items = parseThumbnailElements(galleryElement);

            // define options (if needed)
            options = {
                history: false,
                tapToToggleControls: false,

                // define gallery index (for URL)
                galleryUID: galleryElement.getAttribute('data-pswp-uid'),

                getThumbBoundsFn: function(index) {
                    // See Options -> getThumbBoundsFn section of documentation for more info
                    var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                        pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                        rect = thumbnail.getBoundingClientRect();

                    return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
                }

            };

            // PhotoSwipe opened from URL
            if(fromURL) {
                if(options.galleryPIDs) {
                    // parse real index when custom PIDs are used
                    // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                    for(var j = 0; j < items.length; j++) {
                        if(items[j].pid == index) {
                            options.index = j;
                            break;
                        }
                    }
                } else {
                    // in URL indexes start from 1
                    options.index = parseInt(index, 10) - 1;
                }
            } else {
                options.index = parseInt(index, 10);
            }

            // exit if index not found
            if( isNaN(options.index) ) {
                return;
            }

            if(disableAnimation) {
                options.showAnimationDuration = 0;
            }

            // Pass data to PhotoSwipe and initialize it
            gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);

            // create variable that will store real size of viewport
            var realViewportWidth,
                useLargeImages = false,
                firstResize = true,
                imageSrcWillChange;

            // beforeResize event fires each time size of gallery viewport updates
            gallery.listen('beforeResize', function() {
                // gallery.viewportSize.x - width of PhotoSwipe viewport
                // gallery.viewportSize.y - height of PhotoSwipe viewport
                // window.devicePixelRatio - ratio between physical pixels and device independent pixels (Number)
                //                          1 (regular display), 2 (@2x, retina) ...


                // calculate real pixels when size changes
                realViewportWidth = gallery.viewportSize.x * window.devicePixelRatio;

                // Code below is needed if you want image to switch dynamically on window.resize

                // Find out if current images need to be changed
                if(useLargeImages && realViewportWidth < 1000) {
                    useLargeImages = false;
                    imageSrcWillChange = true;
                } else if(!useLargeImages && realViewportWidth >= 1000) {
                    useLargeImages = true;
                    imageSrcWillChange = true;
                }

                // Invalidate items only when source is changed and when it's not the first update
                if(imageSrcWillChange && !firstResize) {
                    // invalidateCurrItems sets a flag on slides that are in DOM,
                    // which will force update of content (image) on window.resize.
                    gallery.invalidateCurrItems();
                }

                if(firstResize) {
                    firstResize = false;
                }

                imageSrcWillChange = false;

            });


            // gettingData event fires each time PhotoSwipe retrieves image source & size
            gallery.listen('gettingData', function(index, item) {

                // Set image source & size based on real viewport width
                if( useLargeImages ) {
                    item.src = item.originalImage.src;
                    item.w = item.originalImage.w;
                    item.h = item.originalImage.h;
                } else {
                    item.src = item.mediumImage.src;
                    item.w = item.mediumImage.w;
                    item.h = item.mediumImage.h;
                }

                // It doesn't really matter what will you do here,
                // as long as item.src, item.w and item.h have valid values.
                //
                // Just avoid http requests in this listener, as it fires quite often

            });


            // Note that init() method is called after gettingData event is bound
            gallery.init();
        };

        // loop through all gallery elements and bind events
        var galleryElements = document.querySelectorAll( gallerySelector );

        for(var i = 0, l = galleryElements.length; i < l; i++) {
            galleryElements[i].setAttribute('data-pswp-uid', i+1);
            galleryElements[i].onclick = onThumbnailsClick;
            // Commented out to allow touch devices to scroll past gallery items
            //galleryElements[i].ontouchstart = onThumbnailsClick ;
        }

        // Parse URL and open gallery if it contains #&pid=3&gid=1
        var hashData = photoswipeParseHash();
        if(hashData.pid && hashData.gid) {
            openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
        }
    };

    /* Define DOM element */
    //var myElement = document.querySelectorAll('.thumbnailGallery')[0];

    // execute above function
    initPhotoSwipeFromDOM( '.gallery-thumbnail a' );

});
