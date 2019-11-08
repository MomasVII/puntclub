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
 * This library is fairly advanced due to security concerns,
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

////////// START: how to instantiate upload library and connect to a schema

    //instantiate upload library
    require_once(LOCAL.'upload.library.php');
    $upload = new upload(); //set upload location relative to current location

////////// END: how to instantiate upload library and connect to a schema

////////// START: how to do a simple file upload

    //check that file data exists from test form
    if(isset($_FILES) && !empty($_FILES)){
        //set the destination directory
        $upload->set_destination(LOCAL.'dyn_in');;

        //start the upload
        $upload->file($_FILES['test']);

        //if you retain the original file name and a file already exists with that name, it will be overwritten.
        $result = $upload->upload(true); //set true to retain original file name

        if($result['status']){
            $print .= '<p>Simple raw upload succeeded.</p>';
            //print_r($result); //uncomment to see raw data output
        }else{
            $print .= '<p>Simple raw upload failed.</p>';
        }
    }

////////// END: how to do a simple file upload

////////// START: how to do validation and file upload

    //check that file data exists from test form
    if(isset($_FILES) && !empty($_FILES)){
        //set the destination directory
        $upload->set_destination(LOCAL.'dyn_in');;

        //start the upload
        $upload->file($_FILES['test']);

        //set maximum file size in megabytes
        $upload->set_max_file_size(1);

        //set allowed mime types as array
        $upload->set_allowed_mime_types(array('application/pdf'));

        $result = $upload->upload(); //set true to retain original file name

        if($result['status']){
            $print .= '<p>Validated upload succeeded.</p>';
            //print_r($result); //uncomment to see raw data output
        }else{
            $print .= '<p>Validated upload failed.</p>';
        }
    }

////////// END: how to do validation and  file upload

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: upload.library.php');
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

        <h2>Usage Test Form</h2>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">

            <label for="test">Upload</label>
            <input type="file" id="test" name="test"><br />

            <input type="submit" value="Submit"><br />
        </form>

        <hr />

        <h2>Usage Results</h2>

        <?php if(isset($print) && !empty($print)){echo $print;} ?>

    </body>
</html>