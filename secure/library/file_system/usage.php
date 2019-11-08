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

////////// START: how to instantiate file_system

    //instantiate shortcut
    require_once(LOCAL.'file_system.library.php');
    $file_system = new file_system();

////////// END: how to instantiate file_system

////////// START: how make a new directory

    //specify the path you want created, can be as deep as you want, all directories needed will be made
    $dir_make = $file_system->mkdir(LOCAL.'test1/test2/'); //can also specify mode, default is 0775

    if(!empty($dir_make)){
        $print .= '<p>Directory make succeeded.</p>';
    }else{
        $print .= '<p>Directory make read failed.</p>';
    }

////////// END: how make a new directory

////////// START: how to recursively read all files and folders contained in a directory

    //read through the directory (you cannot specify a blank directory)
    $file_read = $file_system->file_iterator(LOCAL.'test1');

    if(!empty($file_read)){
        $print .= '<p>Recursive file read succeeded.</p>';
        //print_r($file_read); //uncomment to see raw data output
    }else{
        $print .= '<p>Recursive file read failed.</p>';
    }

////////// END: how to recursively read all files and folders contained in a directory

////////// START: how to recursively read all folders contained in a directory

    //read through the directory (you cannot specify a blank directory)
    $dir_read = $file_system->directory_iterator(LOCAL.'test1');

    if(!empty($dir_read)){
        $print .= '<p>Recursive folder read succeeded.</p>';
        //print_r($dir_read); //uncomment to see raw data output
    }else{
        $print .= '<p>Recursive folder read failed.</p>';
    }

////////// END: how to recursively read all folders contained in a directory

////////// START: how to copy a directory including all of it's contents to a new location, can also be applies to files

    //specify the source and destination path, can be as deep as you want, all directories needed will be made
    $dir_copy = $file_system->rcopy(LOCAL.'test1', LOCAL.'test1_copy'); //can also specify mode, default is 0775

    if(!empty($dir_copy)){
        $print .= '<p>Directory copy succeeded.</p>';
    }else{
        $print .= '<p>Directory copy read failed.</p>';
    }

////////// END: how to copy a directory including all of it's contents to a new location, can also be applies to files

////////// START: how to change the owner of directory and all it's contents, can also be applied to files

    //specify the path and owner by UID, this will only work on objects that PHP has write privs to
    $dir_chown = $file_system->rchown(LOCAL.'test1_copy', 48);

    if(!empty($dir_chown)){
        $print .= '<p>Directory CHOWN succeeded.</p>';
    }else{
        $print .= '<p>Directory CHOWN failed.</p>';
    }

////////// END: how to change the owner of directory and all it's contents, can also be applied to files

////////// START: how to change the group of directory and all it's contents, can also be applied to files

    //specify the path and group by GID, this will only work on objects that PHP has write privs to
    $dir_chgrp = $file_system->rchown(LOCAL.'test1_copy', 48);

    if(!empty($dir_chgrp)){
        $print .= '<p>Directory CHGRP succeeded.</p>';
    }else{
        $print .= '<p>Directory CHGRP failed.</p>';
    }

////////// END: how to change the group of directory and all it's contents, can also be applied to files

////////// START: how to read basic file system statistics of a directory or file

    //specify the path
    $stats = $file_system->stats(LOCAL.'test.jpg');

    if(!empty($stats)){
        $print .= '<p>Directory statistics read succeeded.</p>';
        //print_r($stats); //uncomment to see raw data output
    }else{
        $print .= '<p>Directory statistics read failed.</p>';
    }

////////// END: how to read basic file system statistics of a directory or file

////////// START: how to check that a file or directory exists

    //specify the path to the file or directory
    $exists = $file_system->object_exists(LOCAL.'test.jpg');

    if(!empty($exists)){
        $print .= '<p>Object exists check succeeded.</p>';
    }else{
        $print .= '<p>Object exists check failed.</p>';
    }

////////// END: how to check that a file or directory exists

////////// START: how to check that a directory exists and is actually a directory

    //specify the path to the directory
    $is_dir = $file_system->directory_exists(LOCAL.'test1_copy');

    if(!empty($is_dir)){
        $print .= '<p>Directory exists check succeeded.</p>';
    }else{
        $print .= '<p>Directory exists check failed.</p>';
    }

////////// END: how to check that a directory exists and is actually a directory

////////// START: how to check that a file exists and is actually a file

    //specify the path to the file
    $is_file = $file_system->file_exists(LOCAL.'test.jpg');

    if(!empty($is_file)){
        $print .= '<p>File exists check succeeded.</p>';
    }else{
        $print .= '<p>File exists check failed.</p>';
    }

////////// END: how to check that a file exists and is actually a file

////////// START: how to read the mime type of a file

    //NOTE: if you need to validate the mime type of a file, look to the validate library

    //specify the path to the file
    $mime = $file_system->mime_type(LOCAL.'test.jpg');

    if(!empty($mime)){
        $print .= '<p>Mime check succeeded.</p>';
        //print_r($mime); //uncomment to see raw data output
    }else{
        $print .= '<p>Mime check failed.</p>';
    }

////////// END: how to read the mime type of a file

////////// START: how to count the contents of a directory

    //NOTE: if you need to validate the mime type of a file, look to the validate library

    //specify the path to the directory
    $count = $file_system->object_count(LOCAL.'count');

    if(!empty($count)){
        $print .= '<p>Object count succeeded.</p>';
        //print_r($count); //uncomment to see raw data output
    }else{
        $print .= '<p>Object count failed.</p>';
    }

////////// END: how to count the contents of a directory

////////// START: how to delete a directory and all of it's contents

    //specify the path you want to delete, all contained files and directories will also be deleted
    $dir_delete = $file_system->rrmdir(LOCAL.'test1');

    if(!empty($dir_delete)){
        $print .= '<p>Directory delete succeeded.</p>';
    }else{
        $print .= '<p>Directory delete failed.</p>';
    }

////////// END: how to delete a directory and all of it's contents

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: file_system.library.php');
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