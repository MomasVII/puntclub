<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Build: Rare_Site_Core_Framework
// Version 2.1.3
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// SCOPE SETTINGS AND INSTANTIATION ////////////////////////////////////////////////////////////////////////////////

//configure relative location to root directory eg: '../' or ''
define('ROOT', '../');

//configure local directory reference (usually blank)
define('LOCAL', '');

//name the framework libraries you need in scope (cross dependencies mean the order matters)
$required_libraries = array(
    //recommended default set
    'error_handler',
    'mysqli_db',
    'validate',
    'session',
    'sentry',
    'shortcut',
);

//name the site classes you need in scope
$required_classes = array(
    'auth',
    'subscriber',
    'product',
    'email',
);

//initialize the framework
require(LOCAL . 'secure/config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LOGIC ///////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
*   NOTE: $response needs to begin with success:, error: or alert: to display on the frontend correctly
*   the the error type prefix (i.e. error: ) doesn't display on the front end.
*/
$response = '';
$state_print = '';
$subscriber_state = '';

//set default print vars
$product_name_print = 'Not found';
$product_sku_print = '?';
$product_term = 'Unknown';
$status_print = 'Unknown';
$data_filter = 'Unknown';
$data_needle = 'N/A';
$delivery_interval = 'Unknown';
$start_date = 'Unknown';
$paid_date = 'Unknown';
$expiry_date = 'Unknown';
$last_delivery = '';
$paid_method = 'Unknown';
$paid_reference = 'Unknown';
$edit_paid_reference = 'disabled';
$download_history_print = '';


//make sure an update id is specified
if (isset($_GET['id']) && !empty($_GET['id']) || $_GET['id'] > 0) {

    //if post data is set
    if(isset($_POST) && !empty($_POST) && $_POST['action'] === 'update'){

        //attempt to update the subscriber
        $update = $subscriber->update($_GET['id']);
        if($update['boolean']){
            $response = 'Success: '.$update['response'];
        }else{
            $response = 'Error: '.$update['response'];
        }
    }

    //listen for eft approval
    if (isset($_GET['eft_confirm']) && ($_GET['eft_confirm'] === 'deny' || $_GET['eft_confirm'] === 'allow')) {
        $eft_approve = $subscriber->eft_approve($_GET['id'], $_GET['eft_confirm']);
        if($eft_approve['boolean']){
            $response = 'Success: '.$eft_approve['response'];
        }else{
            $response = 'Error: '.$eft_approve['response'];
        }
    }

    //listen for subscriber invoice pdf download
    if (isset($_GET['download_invoice']) && $_GET['download_invoice'] === '1') {
        $download = $subscriber->download_invoice($_GET['id']);
        if($download['boolean']){
            $response = 'Success: '.$download['response'];
        }else{
            $response = 'Error: '.$download['response'];
        }
    }

    //listen for subscriber termination
    if (isset($_GET['terminate']) && $_GET['terminate'] === '1') {
        $terminate = $subscriber->terminate($_GET['id']);
        if($terminate['boolean']){
            $response = 'Success: '.$terminate['response'];
        }else{
            $response = 'Error: '.$terminate['response'];
        }
    }

    //get subscriber data
    $subscriber_data = $subscriber->get_by_id($_GET['id']);
    if (!$subscriber_data['boolean'] || empty($subscriber_data['content'])) {
        $response = 'Error: Subscriber couldn\'t be found, please try again';
    }else{

        //get subscribed product
        $subscribed_product = $product->get_by_id($subscriber_data['content']['product']);
        if ($subscribed_product['boolean']) {
            $product_name_print = htmlentities($subscribed_product['content']['name']);
            $product_sku_print = htmlentities($subscribed_product['content']['sku']);
            $product_term = $subscribed_product['content']['subscription_term'];
        }


        //listen for resend invoice
        if(isset($_POST) && !empty($_POST) && $_POST['action'] === 'resend_invoice'){

            //check for alternate email address
            $alt_email = '';
            if(isset($_POST['email']) && !empty($_POST['email'])){
                $alt_email = $_POST['email'];
            }

            if (!$validate->sanitise_handler($alt_email, 'email')) {
                $response = 'Error: Please enter a valid email address and try again';
            } else {
                //attempt to send invoice
                $email->send_invoice($subscriber_data['content'], $subscribed_product['content'], $alt_email);
                $response = 'Success: The subscriber\'s invoice has been resent';
            }
        }

        //listen for resend download attached
        if(isset($_POST) && !empty($_POST) && $_POST['action'] === 'resend_attached'){

            //check for alternate email address
            $alt_email = '';
            if(isset($_POST['email']) && !empty($_POST['email'])){
                $alt_email = $_POST['email'];
            }

            if (!$validate->sanitise_handler($alt_email, 'email')) {
                $response = 'Error: Please enter a valid email address and try again';
            } else {

                $parent_deliver_class = ROOT.'secure/class/deliver.class.php';
                if (file_exists($parent_deliver_class) && is_readable($parent_deliver_class)) {
                    require $parent_deliver_class;
                    $parent_deliver = new deliver();

                    $product_data_file = $parent_deliver->attachment_paid_tier($subscriber_data['content'], $subscribed_product['content'], false);
                    if($product_data_file['boolean']){

                        //attempt to send invoice
                        if($email->send_attachment($subscriber_data['content'], $subscribed_product['content'], $product_data_file['content'], $alt_email)){
                            $response = 'Success: A delivery email has been sent to your subscriber';
                        }else{
                            $response = 'Error: Your email couldn\'t be sent';
                        }
                    }else{
                        $response = $product_data_file['response'];
                    }
                }else{
                    $response = 'Error: The resend you requested couldn\'t be completed';
                }
            }
        }

        //listen for resend download link
        if(isset($_POST) && !empty($_POST) && $_POST['action'] === 'resend_link'){

            //check for alternate email address
            $alt_email = '';
            if(isset($_POST['email']) && !empty($_POST['email'])){
                $alt_email = $_POST['email'];
            }

            if (!$validate->sanitise_handler($alt_email, 'email')) {
                $response = 'Error: Please enter a valid email address and try again';
            } else {

                //attempt to send invoice
                $resend_link = $subscriber->resend_download_link($subscriber_data['content']['subscriber_id'], $alt_email);
                if($resend_link['boolean']){
                    $response = 'Success: '.$resend_link['response'];
                }else{
                    $response = 'Error: '.$resend_link['response'];
                }
            }
        }

        //listen for subscriber data download
        if (isset($_GET['download_data']) && $_GET['download_data'] === '1') {

            $parent_deliver_class = ROOT.'secure/class/deliver.class.php';
            if (file_exists($parent_deliver_class) && is_readable($parent_deliver_class)) {
                require $parent_deliver_class;
                $parent_deliver = new deliver();

                $download = $parent_deliver->download_paid_tier($subscribed_product['content'], $subscriber_data['content']);
                if($download['boolean']){
                    $response = 'Success: '.$download['response'];
                }else{
                    $response = 'Error: '.$download['response'];
                }
            }else{
                $response = 'Error: The download you requested couldn\'t be completed';
            }
        }

        //set subscription status
        $subscribed = false;
        $status_print = 'Expired';
        if ($subscriber_data['content']['expiry_time'] > time() && $subscriber_data['content']['manually_terminated'] === 0) {
            if (isset($subscriber_data['content']['renew_parent_id']) && !empty($subscriber_data['content']['renew_parent_id']) && $subscriber_data['content']['renew_parent_id'] > 0) {
                $status_print = 'Subscribed - Renewed';
            } else {
                $status_print = 'Subscribed - New';
            }

            $subscribed = true;
        } elseif ($subscriber_data['content']['manually_terminated'] > 0) {
            $status_print = 'Terminated';
        } elseif (isset($subscriber_data['content']['renew_child_id']) && !empty($subscriber_data['content']['renew_child_id']) && $subscriber_data['content']['renew_child_id'] > 0) {
            $status_print = 'Renewed';
        }

        //set data filter
        if (!empty($subscriber_data['content']['product_data_filter'])) {
            $data_filter = htmlentities(ucwords($subscriber_data['content']['product_data_filter']));
        }

        //set data needle
        if (!empty($subscriber_data['content']['product_data_needle'])) {
            $data_needle = htmlentities(implode(' / ', (array)json_decode($subscriber_data['content']['product_data_needle'])));
        }

        //set delivery interval
        if (!empty($subscriber_data['content']['product_delivery_interval'])) {
            $delivery_interval = htmlentities(ucwords($subscriber_data['content']['product_delivery_interval']));
        }

        //set start date - calculated be expiry and subscription term cause old method of using paid date is confusing when dealing with renewed records that pay before previous expiry
        $start_date = date('d/m/Y', strtotime('- '.$product_term.' months', $subscriber_data['content']['expiry_time']));

        //set paid date
        if (!empty($subscriber_data['content']['paid_time'])) {
            $paid_date = date('d/m/Y', $subscriber_data['content']['paid_time']);
        }

        //set expiry date
        if (!empty($subscriber_data['content']['expiry_time'])) {
            $expiry_date = date('d/m/Y', $subscriber_data['content']['expiry_time']);
        }

        //set last delivery date
        if (!empty($subscriber_data['content']['last_delivery_time'])) {
            $last_delivery = date('d/m/Y', $subscriber_data['content']['last_delivery_time']);
        }

        //set payment type
        if (!empty($subscriber_data['content']['paid_method'])) {
            $paid_method = htmlentities(ucwords($subscriber_data['content']['paid_method']));

            if($subscriber_data['content']['paid_method'] === 'eft'){
                $edit_paid_reference = 'required';
            }
        }

        //set payment reference
        if (!empty($subscriber_data['content']['paid_reference'])) {
            $paid_reference = htmlentities($subscriber_data['content']['paid_reference']);
        }

        // get $subscriber_state
        $subscriber_state = htmlentities($subscriber_data['content']['business_state']);

        //get download history
        $download_history_select = $subscriber->get_download_history($subscriber_data['content']['subscriber_id']);
        if ($download_history_select['boolean']) {
            $download_history_print = '
                <div class="fixed-height">
                    <table width="100%">
                        <thead>
                            <tr>
                                <th>IP Address</th>
                                <th>Time and Date</th>
                            </tr>
                        </thead>
                        <tbody>
            ';

            foreach ($download_history_select['content'] as $d) {
                $download_history_print .= '
                    <tr>
                        <td>' . $d['end_user_ip'] . '</td>
                        <td>' . date('d/m/Y, h:i:sa', $d['insert_time']) . '</td>
                    </tr>
                ';
            }

            $download_history_print .= '
                        </tbody>
                    </table>
                </div>
            ';
        } else {
            $download_history_print = $download_history_select['response'];
        }

        /* Set options for state dropdown */
        if (isset($stateArray) && !empty($stateArray)) {
			$state_print .='<option value="">Please select</option>';
            foreach ($stateArray as $key => $value) {
                if ( $key === $subscriber_state) {
                    $state_print .='<option value="'.$key.'" selected>'.$value.'</option>';
                } else {
                    $state_print .='<option value="'.$key.'">'.$value.'</option>';
                }
            }
        }
    }
}else{
    //send back to user list
    header('Location: '.LOCAL.'subscriber.html');
    die();
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// INCLUDE DEFINITIONS /////////////////////////////////////////////////////////////////////////////////////////////

//head include
define('HEAD', LOCAL.'secure/include/head.include.php');

//foot include
define('FOOT', LOCAL.'secure/include/foot.include.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// HEAD DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the name of the individual page  - delimiter: N/A
define('PAGE', 'Update Subscriber');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Update Subscriber');

//site author
define('AUTHOR', 'raremedia pty ltd');

//define the individual page styles (you can link or write inline) - delimiter: RETURN
define('STYLES', '
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/foundation.min.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/flatpickr.min.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/typography.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/navigation.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/base.css" />
');

//define the individual page javascript that runs at the start of the page - delimiter: RETURN
define('HEAD_JS', '');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// FOOT DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the individual page javascript that runs at the end of the page - delimiter: RETURN
define('FOOT_JS', '
    <script defer type="text/javascript" src="web/script/jquery-3.2.1.min.js"></script>
    <script defer type="text/javascript" src="web/script/form_validator.js"></script>
    <script defer type="text/javascript" src="web/script/foundation.min.js"></script>
    <script defer type="text/javascript" src="web/script/what-input.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/flatpickr.min.js"></script>
    <script defer type="text/javascript" src="web/script/tag-box.js"></script>
    <script defer type="text/javascript" src="web/script/subscriber_update.page.js"></script>
');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(LOCAL . 'secure/page/subscriber_update.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
