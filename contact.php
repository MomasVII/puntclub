<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Build: rare_core
// Version 2.1.1
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// SCOPE SETTINGS AND INSTANTIATION ////////////////////////////////////////////////////////////////////////////////

//configure relative location to root directory eg: '../' or ''
define('ROOT', '');

//configure local directory reference (usually blank)
define('LOCAL', '');

//name the framework libraries you need in scope (cross dependencies mean the order matters)
$required_libraries = array();

//name the site classes you need in scope
$required_classes = array(
);

//initialize the framework
require(ROOT . 'secure/config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LOGIC ///////////////////////////////////////////////////////////////////////////////////////////////////////////

//set default logic response
$response = '';

//set send email method
function send_email($form){
    global $response, $validate;

    $response = '
        <div id="form_error" class="alert callout">
            <p>Your message couldn\'t be sent<br />Please try again</p>
        </div>
    ';

    //check recaptcha
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){

       //your site secret key
       $secret = '6LcvW5IUAAAAAL4JZgB3I3jWmutqmQr--ggkY_4c';

       //get verify response data
       $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
       $responseData = json_decode($verifyResponse);
       if(!$responseData->success) {
           return false;
       }
   }else{
       return false;
   }

    //make sure the referrer is correct, bot-check field is empty and csrf is valid
    if (!$validate->check_referer() || !empty($meta['check'])) {
        return false;
    }

    //set email parameters
    $subject = 'rplcon.com.au - Automated Contact Form Email';
    $form['SUBJECT'] = $subject;
    $to = 'rui@rplcon.com.au';

    //pull the correct template file
    $html_content = file_get_contents(ROOT . 'secure/include/contact.template.html');
    if (!$html_content) {
        return false;
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

    //instantiate PHPMailer
    require 'secure/side_load/mailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;

    //verbose debug output
    //$mail->SMTPDebug = 3;

    //set SMTP mail send
    $mail->isSMTP();

    //set SMTP server authentication (details for mail.rare.systems)
    $mail->Host = 'mail.rare.systems';
    $mail->SMTPAuth = true;
    $mail->Username = 'security@rare.com.au';
    $mail->Password = 'Gn86wErNu2';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    //set sender(s) and recipient(s)
    $mail->setFrom('security@rare.com.au', ''.TITLE.' Contact Form Submission');
    $mail->addAddress($to);
    $mail->addAddress('raremedia.au@gmail.com'); //additional email inspection
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //set HTML content
    $mail->isHTML(true);

    //set content parameters
    $mail->Subject = $subject;
    $mail->Body    = $html_content;
    $mail->AltBody = 'rplcon.com.au - Automated Contact Form Email (requires HTML)';

    //send mail
    $mail->send();

    $response = '
        <div id="form_success" class="success callout">
            <p>Your message is on it\'s way!</p>
        </div>
    ';

    return true;
}


if(isset($_POST) && !empty($_POST)){

    //call send email method
    send_email($_POST);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// INCLUDE DEFINITIONS /////////////////////////////////////////////////////////////////////////////////////////////

//head include
define('HEAD', ROOT . 'secure/include/head.include.php');

//foot include
define('FOOT', ROOT . 'secure/include/foot.include.php');

//contact
define('CONTACT', ROOT . 'secure/include/contact.include.php');


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// HEAD DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the name of the individual page  - delimiter: N/A
define('PAGE', 'Contact Us');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Get in touch with RPL Construction');

//define the individual page styles - delimiter: COMMA
define('STYLES', '
    '.ROOT. 'web/style/foundation.min.css,
    '.ROOT. 'web/style/typography.css,
    '.ROOT. 'web/style/base.css,
    '.ROOT. 'web/style/header.css,
    '.ROOT. 'web/style/home.css,
    '.ROOT. 'web/style/form.css,
    '.ROOT. 'web/style/footer.css,
    '.ROOT. 'web/style/navigation.css,
');

//define the individual page javascript that runs at the start of the page - delimiter: COMMA
define('HEAD_JS', ''
    //.ROOT.'web/css/YOUR_HEADER_JS_HERE.js,
);


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// FOOT DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the individual page javascript that runs at the end of the page - delimiter: COMMA
define('FOOT_JS', '

    '.ROOT.'web/script/jquery-3.2.1.min.js,
    '.ROOT.'web/script/loadsh.js,
    '.ROOT.'web/script/what-input.js,
    '.ROOT.'web/script/foundation.min.js,
    '.ROOT.'web/script/tweenmax.min.js,
    '.ROOT.'web/script/resizehandler.js,
    '.ROOT.'web/script/navigation.js,
    '.ROOT.'web/script/formhandler.js,
    '.ROOT.'web/script/gaTrack.js,
    '.ROOT.'web/script/svg.js,
    '.ROOT.'web/script/contact.page.js,
');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(ROOT . 'web/page/contact.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
