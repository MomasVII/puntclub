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

    //security salt (should be unique to each site)
    define('SALT', 'site_salt');

    //instantiate validate
    require_once(LOCAL.'validate.library.php');
    $validate = new validate();

////////// END: how to instantiate validate

////////// START: how to check the referer

    //check if the referer is the same as the script location
    $referer_check = $validate->check_referer();

    if($referer_check){
        $print .= '<p>Referer matched.</p>';
    }else{
        $print .= '<p>Referer did not match or was not set.</p>';
    }

////////// END: how to check the referer

////////// START: how to do a quick generic clean on an array or string

    //set a test dirty array
    $dirty_array = array();
    $dirty_array['string_test'] = 'w=P`*fj^<div>;V8KG.a';
    $dirty_array[2] = 'f4E7iG428XTALTtV';
    $dirty_array['integer_test'] = 2404287235406747;

    //send the dirty array to the quick clean function
    $less_dirty_array = $validate->quick_clean($dirty_array);

    if(!empty($less_dirty_array)){
        $print .= '<p>Array quick clean succeeded.</p>';
        //print_r($less_dirty_array); //uncomment to see raw data output
    }else{
        $print .= '<p>Array quick clean failed.</p>';
    }


    //set a dirty test string
    $dirty_string = '<p>e+T%,b9w!jH2L[QF';

    //send the dirty string to the quick clean function
    $less_dirty_string = $validate->quick_clean($dirty_string);

    if(!empty($less_dirty_string)){
        $print .= '<p>String quick clean succeeded.</p>';
        //print_r($less_dirty_string); //uncomment to see raw data output
    }else{
        $print .= '<p>String quick clean failed.</p>';
    }

////////// END: how to do a quick generic clean on an array or string

////////// START: how to encode and decode JSON data

    //set a test array (can be keyed or not)
    $test_array = array(
        "foo" => "bar",
        "bar" => "foo",
    );

    //attempt to encode HTML
    $encoded_json = $validate->encode_json($test_array);

    if($encoded_json){
        $print .= '<p>JSON encoding succeeded.</p>';
        //print_r($encoded_json); //uncomment to see raw data output
    }else{
        $print .= '<p>JSON encoding failed.</p>';
    }

    //take the encoded json from above and decode it
    $decoded_json = $validate->decode_json($encoded_json);
    if($decoded_json){
        $print .= '<p>JSON decoding succeeded.</p>';
        //print_r($decoded_json); //uncomment to see raw data output
    }else{
        $print .= '<p>JSON decoding failed.</p>';
    }

////////// END: how to encode and decode JSON data

////////// START: how to encode and decode bas64 data

//set a test array (can be keyed or not)
$test_string = 'Dutpj0fyfcIQdm1BwmTBr0KYWT6CiFF9izgMNKdd0HG6UOGm7nnqfUCsJ9o4LXZGpR7aERwL8Z4BvbDgWL0WEbfVSS9HRQG7nDPHDu';

//attempt to encode HTML
$encoded_base64 = $validate->encode_base64($test_string);

if($encoded_base64){
    $print .= '<p>BASE64 encoding succeeded.</p>';
    //print_r($encoded_base64); //uncomment to see raw data output
}else{
    $print .= '<p>BASE64 encoding failed.</p>';
}

//take the encoded json from above and decode it
$decoded_base64 = $validate->decode_base64($encoded_base64);
if($decoded_base64){
    $print .= '<p>BASE64 decoding succeeded.</p>';
    //print_r($decoded_base64); //uncomment to see raw data output
}else{
    $print .= '<p>BASE64 decoding failed.</p>';
}

////////// END: how to encode and decode bas64 data

////////// START: how to encode and decode raw html

    //set a string of html
    $raw_html = '
        <!DOCTYPE html>
        <html>
            <body>
                <h1>Heading</h1>
                <p>Paragraph with an & character</p>
            </body>
        </html>
    ';

    //attempt to encode HTML
    $encoded_html = $validate->encode_html($raw_html);

    if($encoded_html){
        $print .= '<p>HTML encoding succeeded.</p>';
        //print_r($encoded_html); //uncomment to see raw data output
    }else{
        $print .= '<p>HTML encoding failed.</p>';
    }

    //take the encoded html from above and decode it
    $decoded_html = $validate->decode_html($encoded_html);
    if($decoded_html){
        $print .= '<p>HTML decoding succeeded.</p>';
        //print_r($decoded_html); //uncomment to see raw data output
    }else{
        $print .= '<p>HTML decoding failed.</p>';
    }

////////// END: how to encode and decode raw html

////////// START: how to encode and decode a URL string

    //set a string of html
    $raw_url = 'http://framework.rare.com.au/validate/usage.php?test=test';

    //attempt to encode URL
    $encoded_url = $validate->encode_url($raw_url);

    if($encoded_url){
        $print .= '<p>URL encoding succeeded.</p>';
        //print_r($encoded_url); //uncomment to see raw data output
    }else{
        $print .= '<p>URL encoding failed.</p>';
    }

    //take the encoded URL from above and decode it
    $decoded_url = $validate->decode_url($encoded_url);
    if($decoded_url){
        $print .= '<p>URL decoding succeeded.</p>';
        //print_r($decoded_url); //uncomment to see raw data output
    }else{
        $print .= '<p>URL decoding failed.</p>';
    }

////////// END: how to encode and decode a URL string

////////// START: how to camel case a string based on spaces

    //set a string
    $raw_string = 'oirRz yWaCG CKwRC javki';

    //attempt to convert string
    $camel_string = $validate->camel_case($raw_string);

    if($camel_string){
        $print .= '<p>Camel case conversion succeeded.</p>';
        //print_r($camel_string); //uncomment to see raw data output
    }else{
        $print .= '<p>Camel case conversion failed.</p>';
    }

////////// END: how to camel case a string based on spaces

////////// START: how to sanitise an entire post array to individual data types

//check that post data exists from test form
if(isset($_POST) && !empty($_POST)){

    //define containing variable
    $type_array = array();
    //manually set the data-type for each piece of post data, there's no easy and secure way to do this.
    $type_array['test_ip'] = 'ip'; //IP address
    $type_array['test_int'] = 'int';//integer
    $type_array['test_string_int'] = 'string_int';//alpha-numeric (no spaces allowed)
    $type_array['test_email'] = 'email';//email address
    $type_array['test_url'] = 'url';//url
    $type_array['test_string'] = 'string';//generic string (spaces and numbers allowed)
    $type_array['test_float'] = 'float';
    $type_array['test_restricted'] = 'restricted';
    $type_array['test_skip'] = 'skip';

    $sanitised_data = $validate->sanitise_array($_POST, $type_array, true); //last param is whether or not to allow empty/zero to pass validation.

    if($sanitised_data){
        $print .= '<p>Array sanitise succeeded.</p>';
        //print_r($sanitised_data); //uncomment to see raw data output
    }else{
        $print .= '<p>Array sanitise failed.</p>';
    }
}else{
    $print .= '<p>Array sanitise failed, no _POST data was found.</p>';
}

////////// END: how to sanitise an entire post array to individual data types

////////// START: how to find and verify the mime type of a file

//set a test file to use, there is also a csv and pdf to test with
$test_file = LOCAL.'test.jpg';

//this function will check the file extension and then compare it to the mime-type it should be return true/false.
$mime_check = $validate->check_mime($test_file);

//some files will return a false fail depending on the server, magic file and mime array. To fix, go to: mime_array.php
if($mime_check){
    $print .= '<p>Mime type check succeeded.</p>';
}else{
    $print .= '<p>Mime type check failed.</p>';
}

////////// END: how to find and verify the mime type of a file

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: validate.library.php');
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
            <label for="test_ip">IP</label>
                <input type="text" id="test_ip" name="test_ip" value="111.111.111.111"><br />

            <label for="test_int">Integer</label>
                <input type="text" id="test_int" name="test_int" value="3641"><br />

            <label for="test_string_int">Alpha Numeric</label>
                <input type="text" id="test_string_int" name="test_string_int" value="69zwi0sizDlkVh78ndFg"><br />

            <label for="test_email">Email</label>
                <input type="text" id="test_email" name="test_email" value="test@rare.com.au"><br />

            <label for="test_url">URL</label>
                <input type="text" id="test_url" name="test_url" value="http://framework.rare.com.au/index.html?test=test"><br />

            <label for="test_string">String</label>
                <input type="text" id="test_string" name="test_string" value="uqYtJupwxkbSWGxzirkD"><br />

            <label for="test_float">Float</label>
                <input type="text" id="test_float" name="test_float" value="546.86"><br />

            <label for="test_restricted">Restricted</label>
                <input type="text" id="test_restricted" name="test_restricted" value="tNblRMdWKqPtrdaEteTm"><br />

            <label for="test_skip">Skip</label>
                <input type="text" id="test_skip" name="test_skip" value="eTEpUXdZYduDIYFKArcS"><br />

            <input type="submit" value="Submit"><br />
        </form>

        <hr />

        <h2>Usage Results</h2>

        <?php if(isset($print) && !empty($print)){echo $print;} ?>

    </body>
</html>