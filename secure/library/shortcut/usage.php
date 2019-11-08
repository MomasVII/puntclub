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

////////// START: how to instantiate shortcut

    //instantiate shortcut
    require_once(LOCAL.'shortcut.library.php');
    $shortcut = new shortcut();

////////// END: how to instantiate validate

////////// START: how to do a context sensitive header

    //call anywhere before headers are sent, the location specified is relative your current served location.
    //$shortcut->relative_header('__README.txt');

    $print .= '<p>Relative header skipped.</p>';

////////// END: how to do a context sensitive header

////////// START: how to print a JS redirect from php

    //call a JS redirect anywhere (even illegally from inside classes) not to be used illegally in production code.
    //$shortcut->js_redirect('__README.txt');

    $print .= '<p>JS redirect skipped.</p>';

////////// END: how to print a JS redirect from php

////////// START: how to print a JS alert from php

    //call a JS alert anywhere (even illegally from inside classes) not to be used illegally in production code.
    //$shortcut->js_alert('JS ALERT!');

    $print .= '<p>JS alert skipped.</p>';

////////// END: how to print a JS alert from php

////////// START: how to truncate a string

    //set a test string
    $test_string = '9Rf4Ro1GN3n9D8q';

    //truncate the string, the tail is also counted in the limit eg: '...'
    $truncated_string = $shortcut->truncate_string($test_string, 10); //is possible to set a custom tail string

    $print .= '<p>Truncate string completed.</p>';
    //print_r($truncated_string); //uncomment to see raw data output

////////// END: how to truncate a string

////////// START: how to generate a random string

    //generate a string and set a limit, it is possible to cause a fatal memory error by setting silly limit
    $random_string = $shortcut->random_string(10);

    $print .= '<p>Random string generation completed.</p>';
    //print_r($random_string); //uncomment to see raw data output

////////// END: how to generate a random string

////////// START: how to translate a time into relative descriptive english

    //set a test time variable
    $test_time = time() + 3600;

    //translate to english
    $time_relative_descriptive = $shortcut->time_relative_descriptive($test_time);

    $print .= '<p>Relative descriptive time succeeded.</p>';
    //print_r($time_relative_descriptive); //uncomment to see raw data output

////////// END: how to translate a time into relative descriptive english

////////// START: how to translate a time into a counter array

    //set a test time variable
    $test_time = 36563;

    //translate to counter array
    $time_counter = $shortcut->time_counter($test_time);

    $print .= '<p>Counter time succeeded.</p>';
    //print_r($time_counter); //uncomment to see raw data output

////////// END: how to translate a time into a counter array

////////// START: how to append an ordinal to an int

    //set a test int variable
    $test_int = 3;

    //append ordinal to integer, even works on factored numbers
    $ordinal_int = $shortcut->append_ordinal($test_int);

    $print .= '<p>Ordinal append succeeded.</p>';
    //print_r($ordinal_int); //uncomment to see raw data output

////////// END: how to append an ordinal to an int

////////// START: how to inspect memory usage of an operation

    //grab memory stats after PHP is finished it's current operation, this method will print_r() the results
    //register_shutdown_function(array('shortcut', 'system_memory_usage'));

    $print .= '<p>Memory usage skipped.</p>';

////////// END: how to inspect memory usage of an operation

////////// START: how to inspect cpu/user time usage

    //grab time stats after PHP is finished it's current operation, this method will print_r() the results
    //register_shutdown_function(array('shortcut', 'system_time_usage'));

    $print .= '<p>CPU and user time usage skipped.</p>';

////////// END: how to inspect cpu/user time usage

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: shortcut.library.php');
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