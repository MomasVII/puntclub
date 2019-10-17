function preloadVideos(){
    var video_src_list = [];//video list can be set via reflexion
    preload = new createjs.LoadQueue();
    /*
     preload.on("fileload", handleFileLoad, this);
     preload.on("progress", handleOverallProgress, this);
     preload.on("fileprogress", handleFileProgress, this);
     preload.on("error", handleFileError, this);
     */
    preload.setMaxConnections(5);

    $('video source:first').each(function () {
        //console.log(this);
        if(this.src){
            video_src_list.push(this.src);
        }
    });
    //console.info('videos',video_src_list);
    preload.loadManifest(video_src_list);
}
function handleFileLoad(event) {

    // Get a reference to the loaded image (<img/>)
    //var img = event.result;
    //console.log(event.item.src);

    // getting the images and their index for later sorting
    ///map[map.length] = {'src':String(String(event.src).toLowerCase()).replace('assets/image','').replace('.jpg','').replace('.png',''),'data':img};

}
// File progress handler
function handleFileProgress(event) {
    //console.log(event);

}

// Overall progress handler
function handleOverallProgress(event) {
    var perc = Math.round(preload.progress*100);
    //update the loading text
    $("#loader_content").text(perc+'%');

    //if file preload complete
    if(preload.progress>= 1){
        //remove the loader modal
        //$("#loader_container").fadeOut(1000, function() { $(this).remove() });
        preloadVideos();//start preloading the videos
        return;
    }
}

// An error happened on a file
function handleFileError(event) {
    console.error('error',event);
}
//hardcode the file list per url
var img_src_list = [
    "/web/image/demo/demo-project-slide01.jpg"
];
//if there are images to preload
if(img_src_list.length) {
    //create the preloader
    preload = new createjs.LoadQueue();
    preload.on("fileload", handleFileLoad, this);
    preload.on("progress", handleOverallProgress, this);
    preload.on("fileprogress", handleFileProgress, this);
    preload.on("error", handleFileError, this);
    //preload.setMaxConnections(5);
    preload.loadManifest(img_src_list);
}else{
    //no images to preload, remove modal
    //$("#loader_container").fadeOut().remove();
}