<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Build: Rare_Framework_Testing_Controller
// Version 0.0.4
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//SETTINGS /////////////////////////////////////////////////////////////////////////////////////////////////////////////
//configure relative location to root directory eg: '../' or ''
define('ROOT', '../');
define('LOCAL', '');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//NOTES, COMMENTS AND GOTCHYAS /////////////////////////////////////////////////////////////////////////////////////////
/*
 *
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//CONTROL //////////////////////////////////////////////////////////////////////////////////////////////////////////////

//turn on all errors (an error log can also be found in the framework root directory)
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(2047);

//set container variable for usage returns
$print = '';

////////// START: how to instantiate validate

    //file_system library is a dependency for this library
    require_once(ROOT.'file_system/file_system.library.php');
    $file_system = new file_system();

    //instantiate shortcut
    require_once(LOCAL.'image_gd.library.php');
    $image_gd = new image_gd();

////////// END: how to instantiate validate

////////// START: how to load an image and get it's details


    $load = $image_gd->load(LOCAL.'test.jpg');

    if($load){
        $print .= '<p>Image loaded successfully.</p>';

        //there are a number of image parameters made available on load that you can reference at any time
        //print_r('Image width: '.$image_gd->width.'<br />');
        //print_r('Image height: '.$image_gd->height.'<br />');
        //print_r('Image type: '.$image_gd->type.'<br />');
        //print_r('Image attributes: '.$image_gd->attributes.'<br />');
        //print_r('Image bits: '.$image_gd->bits.'<br />');
        //print_r('Image RGB channels: '.$image_gd->channels.'<br />');
        //print_r('Image mime type: '.$image_gd->mime_type.'<br />');
    }else{
        $print .= '<p>Image load failed.</p>';
    }

////////// END: how to load an image and get it's details

////////// START: how to save an image in a specified format

    //save the last loaded image, you can set path image type, compression level and permission mode
    $save = $image_gd->save(LOCAL.'test_save.jpg', IMAGETYPE_JPEG);

    if($save){
        $print .= '<p>Image saved successfully.</p>';
    }else{
        $print .= '<p>Image save failed.</p>';
    }

////////// END: how to load an image and get it's details

////////// START: how to output an image via header to the browser

    //output the last loaded image to the browser, can set type and quality
    //$image_gd->output(IMAGETYPE_JPEG);

    $print .= '<p>Image output skipped.</p>';


////////// END: how to output an image via header to the browser

////////// START: how to resize an image by it's height

    //resize the last loaded image by height while maintaining proportions
    $resize_by_height = $image_gd->resize_by_height(900);

    if($resize_by_height){
        $print .= '<p>Resize by height was successful.</p>';
        //output the image to see the results
        //$image_gd->output(IMAGETYPE_JPEG);
    }else{
        $print .= '<p>Resize by height failed.</p>';
    }

////////// END: how to resize an image by it's height

////////// START: how to resize an image by it's width

    //resize the last loaded image by height while maintaining proportions
    $resize_by_width = $image_gd->resize_by_width(800);

    if($resize_by_width){
        $print .= '<p>Resize by width was successful.</p>';
        //output the image to see the results
        //$image_gd->output(IMAGETYPE_JPEG);
    }else{
        $print .= '<p>Resize by width failed.</p>';
    }

////////// END: how to resize an image by it's width

////////// START: how to resize an image and force a square

    //resize the last loaded image into a forced square
    $resize_square = $image_gd->resize_square(600);

    if($resize_square){
        $print .= '<p>Resize to square was successful.</p>';
        //output the image to see the results
        //$image_gd->output(IMAGETYPE_JPEG);
    }else{
        $print .= '<p>Resize to square failed.</p>';
    }

////////// END: how to resize an image and force a square

////////// START: how to scale an image

    //resize the last loaded image by scale percentage
    $resize_scale = $image_gd->resize_scale(80);

    if($resize_scale){
        $print .= '<p>Resize scale was successful.</p>';
        //output the image to see the results
        //$image_gd->output(IMAGETYPE_JPEG);
    }else{
        $print .= '<p>Resize scale failed.</p>';
    }

////////// END: how to scale an image

////////// START: how to manually resize an image

    //manually resize and image with w/h params in px
    $resize = $image_gd->resize(350, 400);

    if($resize){
        $print .= '<p>Manual resize was successful.</p>';
        //output the image to see the results
        //$image_gd->output(IMAGETYPE_JPEG);
    }else{
        $print .= '<p>Manual resize failed.</p>';
    }

////////// END: how to manually resize an image

////////// START: how to crop an image

    //set the x/y point to crop from, then the w/h
    $crop = $image_gd->crop(0, 0, 350, 350);

    if($crop){
        $print .= '<p>Crop was successful.</p>';
        //output the image to see the results
        //$image_gd->output(IMAGETYPE_JPEG);
    }else{
        $print .= '<p>Crop failed.</p>';
    }

////////// END: how to crop an image


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: image_gd.library.php');
define('CHARSET', 'text/html; charset=utf-8');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo TITLE.' - '.PAGE; ?></title>
        <meta http-equiv="content-type" content="<?php echo CHARSET; ?>" />
    </head>
    <body>
        <h1><?php echo TITLE; ?></h1>

        <h2><?php echo PAGE; ?></h2>

        <p>If you're not sure what this page is for, please open the <a href="<?php echo LOCAL; ?>__README.txt" title="README FILE">README</a> or speak to Gordon MacK</p>

        <hr />

        <h2>Usage Results</h2>

        <?php if(isset($print) && !empty($print)){echo $print;} ?>

    </body>
</html>