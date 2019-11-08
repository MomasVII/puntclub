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

////////// START: how to instantiate email

    //validate library is a dependency for this library
    require_once(ROOT.'validate/validate.library.php');
    $validate = new validate();

    //instantiate email
    require_once(LOCAL.'email.library.php');
    $email = new email();

////////// END: how to instantiate validate

////////// START: how to send an array of data as an email

    //subject must be a utf-8 string
    $subject = 'A new Project has been assigned to you';

    //from is a description rather than an email address
    $from = 'Rare Core';

    //data must be a single dimension key=>pair array
    $data = array('Job Number' => '14-9883', 'Project Name' => 'Backend Build');

    //you can send to one or multiple to recipients by using an array or a string
    //$to = 'specialfae@gmail.com';
    $to = array('gordon@rare.com.au', 'developer@rare.com.au');

    //you can send to one or multiple cc recipients by using an array or a string
    //$cc = 'security@rare.com.au';
    $cc = array('security@rare.com.au', 'domains@rare.com.au');

    //you can send to one or multiple bcc recipients by using an array or a string
    //$bcc = 'domains@rare.com.au';
    $bcc = array('domains@rare.com.au', 'security@rare.com.au');

    //perform send
    $send_array = $email->send_array($subject, $from, $data, $to, $cc, $bcc, 'local-development.rare.com.au');

    if($send_array){
        $print .= '<p>Send array succeeded.</p>';
    }else{
        $print .= '<p>Send array failed.</p>';
    }

////////// END: how to send an array of data as an email

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: email.library.php');
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