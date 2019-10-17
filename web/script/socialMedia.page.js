/*
  Theme Name: Raremedia PHP core_boilerplate
  Author: Lucas Jordan
  Description: Raremedia PHP core_boilerplate social media page javascript
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Fetch Instagram function
#   Document Ready Function
--------------------------------------------------------------*/

/* Ajax request function to load the next count value of images */
function fetchIstagram(url) {

  var nextCount = $(".btn_nextLink").data("next-count")

  //START POST AJAX REQUEST
  $.ajax({
    url: "web/ajax/fetch.instagram.posts.ajax.html",
    type: "POST",
    data: { 'link': url },
    success: function (data, textStatus) {

      /* Successful request call back function */
      var json = JSON.parse(data);

      if ( Object.keys(json).length > 0 ) {
        var next_url = json.pagination.next_url

        /* Get number of image objects returned */
        var count = Object.keys(json.data).length;

        /* Iterate and return the markup for each post/ image */
        for ( var i = 0; i < count; i++ ) {
          var returnDom = '<li class="columns column-block small-6 medium-4">'
          returnDom += '<span class="likes-count"> <i class="icons icons-heart xxsmall"></i> <span class="icon-label"> Likes: </span>'
          returnDom += json.data[i].likes.count
          returnDom += '</span><span class="comments-count"><i class="icons icons-bubble2 xxsmall"></i> <span class="icon-label">  Comments: </span>'
          returnDom += json.data[i].comments.count
          returnDom += '</span> <br>'
          returnDom += '<a href="'+json.data[i].images.thumbnail.url+'" target="_blank">'
          returnDom += '<img src="'+json.data[i].images.thumbnail.url+'" width="'+json.data[i].images.thumbnail.width+'" height="'+json.data[i].images.thumbnail.height+'" /></a>'
          returnDom += '<p><strong>Caption: </strong>'

          /* Handle caption / throws error is missing */
          if( json.data[i].caption != null ) {
            returnDom += json.data[i].caption.text
          }

          returnDom += '<br />'
          returnDom += '<span><strong>User Name: </strong>'
          returnDom += json.data[i].user.username+'</span>'
          returnDom += '<span><strong>Link: </strong> <a href="'+json.data[i].link+'" target="_blank">visit full image</a></span>'
          returnDom += '</p>'
          returnDom += '</li>'

          /* Append item to the ul parent */
          $(".instagram_feed").append( returnDom )
        }

        // update load next images from feed button
        $(".btn_nextLink").remove()
        $(".instagram_feed").parent().append('<a href="#" class="btn_nextLink" data-link="'+next_url+'" data-next-count="'+nextCount+'">Load More</a>')
      }


    },
    error: function (jqXHR, textStatus, errorThrown) {
      // catch error here
    }
  });
  //END POST AJAX REQUEST

}
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

  /* Instantiate Social Media Tabs */
  var elem = new Foundation.Tabs( $("#socialMedia-tabs") );

  /* Bind event to all current and future btn_nextLink elements */
  $(document).on("click", '.btn_nextLink', function(event) {

    event.preventDefault();
    event.stopPropagation();

    if ( $(this).data('link') !== 'undefined' ) {

      /* Update the count number  */
      var nextCount = $(".btn_nextLink").data("next-count")
      var nextLink = $(".btn_nextLink").data("link")
      var index = nextLink.indexOf("&count=") + 7
      nextLink = nextLink.substr(0, index) + nextCount + nextLink.substr(index + 1);
      $(this).attr("data-link", nextLink)

      fetchIstagram( nextLink )
    }

  })

});
