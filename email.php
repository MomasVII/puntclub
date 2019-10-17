<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Build: Rare_Site_Core_Framework
// Version 2.1.3
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// SCOPE SETTINGS AND INSTANTIATION ////////////////////////////////////////////////////////////////////////////////

//configure relative location to root directory eg: '../' or ''
define('ROOT', '');

//configure local directory reference (usually blank)
define('LOCAL', '');

//name the framework libraries you need in scope (cross dependencies mean the order matters)
$required_libraries = array(
    'shortcut',
    'validate',
    'email'
);

//name the site classes you need in scope
$required_classes = array(
    //'auth'
);

//initialize the framework
require(ROOT . 'secure/config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LOGIC ///////////////////////////////////////////////////////////////////////////////////////////////////////////

//set email parameters based on site
$content_map = array(
    'contact' => array(
        'template' => 'contact.html',
        'subject' => 'Contact Enquiry',
        'to' => 'contact@rare.com.au',
        'from' => 'no-reply@rare.com.au',
        'cc' => 'contact@rare.com.au'
    ),
    'job' => array(
        'template' => 'job.html',
        'subject' => 'Job Enquiry',
        'to' => 'contact@rare.com.au',
        'from' => 'no-reply@rare.com.au',
        'cc' => 'contact@rare.com.au'
    )
);

//set send email method
function send_email($form, $meta)
{
    global $email, $auth, $validate, $content_map;

    //set default return values
    $return = array(
        'boolean' => false,
    );

    //make sure the referrer is correct, bot-check field is empty and csrf is valid
    if (!$validate->check_referer() || !empty($meta['check']) /*&& $auth->validate_csrf_token((string)$meta['form'], (string)$meta['csrf'])*/) { //TODO: @gordon CSRF ISN'T WORKING ON CPANEL.RARE.SYSTEMS
        return $return;
    }
    //set email parameters
    $subject = $content_map[$meta['form']]['subject'];
    $to = $content_map[$meta['form']]['to'];
    $from = $content_map[$meta['form']]['from'];
    $cc = $content_map[$meta['form']]['cc'];

    //set subject var for email template content
    $form['SUBJECT'] = $subject;

    //pull the correct template file
    $html_content = file_get_contents(ROOT . 'secure/library/email/template/' . $content_map[$meta['form']]['template']);
    if (!$html_content) {
        return $return;
    }

    //replace the needles in the template haystack with our form params
    if (preg_match_all("/{{(.*?)}}/", $html_content, $template_variable)) {
        foreach ($template_variable[1] as $i => $varname) {
            $value = '';
            if (isset($form[$varname]) && $form[$varname]) {
                $value = $validate->sanitise_handler($form[$varname], 'string', false);
            }
            $html_content = str_replace($template_variable[0][$i], sprintf('%s', $value), $html_content);
        }
    }

    //send the email
    $response = $email->send_array($subject, $from, $html_content, $to, $cc);
    if ($response) {
        $return['boolean'] = true;
    }


    return $return;
    die();
}

//forms submit via GET (should have been POST but can't change JS at time of writing)
//make sure an 'action' is set
if (isset($_POST['meta']['action'])) {

    //make sure form data exists, meta data exists and action is as expected
    if (!empty($_POST['form']) && !empty($_POST['meta']) && (string)$_POST['meta']['action'] == 'send_email') {

        //call send email method
        $response = send_email($_POST['form'], $_POST['meta']);

        //respond in json
        print json_encode($response['boolean']);
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
