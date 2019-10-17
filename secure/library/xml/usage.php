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
 * Comment: Please be aware that no single function here is a golden bullet for security/sanitisation/validation, you
 * need to use common sense and combine these functions as needed. Occasionally you may even need to write something
 * custom to cover all your bases. Take caution and always try to keep security in mind.
 *
 * Note: These functions are not designed to improve user experience, that's what Javascript is for.
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

    //instantiate xml
    require_once(LOCAL.'xml.library.php');
    $xml = new xml();

////////// END: how to instantiate validate

////////// START: how to create an XML friendly response

    //return XML response, must define response type and string, types: 'error', 'true', 'false'
    $xml_response = $xml->xml_response('true', 'success!'); //can set custom root node name

    //no need for if, this function triggers a hard error on failure for diagnostic reasons
    $print .= '<p>XML response completed.</p>';
    //print_r($xml_response); //uncomment to see raw data output

////////// END: how to create an XML friendly response

////////// START: how to convert an array to XML

    //set a test array
    $test_array = array (
        'bla' => 'blub',
        'foo' => 'bar',
        'another_array' => array (
            'stack' => 'overflow',
        ),
    );

    //attempt to convert the array into xml
    $xml_array = $xml->array_to_xml($test_array); //can set custom root node name

    //no need for if, a failure will result in an XML 'error' like above for diagnostic reasons
    $print .= '<p>Array to XML conversion completed.</p>';
    //print_r($xml_array); //uncomment to see raw data output

    //attempt to convert the xml back into an array
    $plain_array = $xml->xml_to_array($xml_array);

    if(!empty($plain_array)){
        $print .= '<p>XML to Array conversion succeeded.</p>';
        //print_r($plain_array); //uncomment to see raw data output
    }else{
        $print .= '<p>XML to Array conversion failed.</p>';
    }

////////// END: how to convert an array to XML

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: xml.library.php');
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