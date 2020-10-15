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
    'paginate',
);

//name the site classes you need in scope
$required_classes = array(
    'auth',
    'subscriber',
    'product',
);

//initialize the framework
require(LOCAL.'secure/config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LOGIC ///////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
*   NOTE: $response needs to begin with success:, error: or alert: to display on the frontend correctly
*   the the error type prefix (i.e. error: ) doesn't display on the front end.
*/
$response = '';
$record_print = '';
$pagination_print = '';

//set pending subscription notification
$pending_response = '';
$pending_count = $subscriber->count_pending();
if($pending_count > 0){
    $pending_response = '
        <div class="columns small-12">
            <div class="warning callout white">
                <p>
                    <span class="icons icons-alert xsmall">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </span>
                    You have ('.$pending_count.') purchase requests requiring review
                </p>
            </div>
        </div>
    ';
}

//set paginate params container to add search filters to
$paginate_params = array();

//listen for expired filter
$expired = 0;
if (isset($_GET['expired']) && $_GET['expired'] === '1') {
    $expired = 1;
    $paginate_params['expired'] = 1;
}

//listen for search string
$keyword = '';
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
    $paginate_params['keyword'] = $keyword;
}

//listen for field string
$field = '';
if (isset($_GET['field']) && !empty($_GET['field'])) {
    $field = trim($_GET['field']);
    $paginate_params['field'] = $field;
}

//get subscribers with filter if set
$all_subscribers = $subscriber->get_search($keyword, $field, $expired);

//if subscribers were found start pagination and print
if ($all_subscribers['boolean']) {

    //set default pagination page increment and reset if page get var is set and valid
    $page_increment = 1;
    if (isset($_GET['p']) && $_GET['p'] > 0) {
        $page_increment = $_GET['p'];
    }

    //set pagination params
    $paginate->set($all_subscribers['content'], $page_increment);

    //allow first and last buttons
    $paginate->show_first_and_last(true);

    //build pagination print
    $pagination_print = $paginate->build_navigation($paginate_params);

    //get pagination params back (including filtered result set)
    $pagination_parameters = $paginate->return_parameters();

    //check that the filtered record set isn't empty
    if (!empty($pagination_parameters['content'])) {

        //set the table headers
        $record_print .= '
                <div class="edit-table">
                    <div class="header">
                        <div class="item">Business Name</div>
                        <div class="item">Delivery Address</div>
                        <div class="item">Contact Name</div>
                        <div class="item">Product Title</div>
                        <div class="item">Update<br />frequency</div>
                        <div class="item">Data<br />Set</div>
                        <div class="item">Payment<br />Type</div>
                        <div class="item">Paid<br />Date</div>
                        <div class="item">Start<br />Date</div>
                        <div class="item">Expiry<br />Date</div>
                    </div>
            ';

        //loop out the records
        foreach ($pagination_parameters['content'] as $c) {

            //get product name
            $subscribed_product_print = 'Not found';
            $subscribed_product = $product->get_by_id($c['product']);
            if ($subscribed_product['boolean']) {
                $subscribed_product_print = $subscribed_product['content']['name'];
            }

            //if the subscription is awaiting EFT approval
            $pending = '';
            if($c['paid_time'] === 0 && $c['paid_method'] === 'eft' && $c['manually_terminated'] === 0){
                $pending = 'class="pending"';
            }

            //if paid time is set
            $paid_date = 'Pending';
            if($c['paid_time'] > 0 && $c['manually_terminated'] === 0){
                $paid_date = date('d/m/Y', $c['paid_time']);
            }

            //if expiry time is set
            $expiry_date = 'Unknown';
            if($c['expiry_time'] > 0 && $c['manually_terminated'] === 0){
                //set start date - calculated be expiry and subscription term cause old method of using paid date is confusing when dealing with renewed records that pay before previous expiry
                $start_date = date('d/m/Y', strtotime('- '.$subscribed_product['content']['subscription_term'].' months', $c['expiry_time']));
                $expiry_date = date('d/m/Y', $c['expiry_time']);
            }elseif($c['manually_terminated'] > 0){
                $start_date = 'Manually';
                $expiry_date = 'Terminated';
            }

            //beautify
            $business_name = 'Unknown';
            if(isset($c['business_name']) && !empty($c['business_name'])){$business_name = htmlentities($c['business_name']);}
            $delivery_email = 'Unknown';
            if(isset($c['delivery_email']) && !empty($c['delivery_email'])){$delivery_email = htmlentities($c['delivery_email']);}
            $contact_name = 'Unknown';
            if(isset($c['contact_name']) && !empty($c['contact_name'])){$contact_name = htmlentities($c['contact_name']);}
            $product_delivery_interval = 'Unknown';
            if(isset($c['product_delivery_interval']) && !empty($c['product_delivery_interval'])){$product_delivery_interval = ucwords($c['product_delivery_interval']);}
            $product_data_filter = 'Unknown';
            if(isset($c['product_data_filter']) && !empty($c['product_data_filter'])){$product_data_filter = ucwords($c['product_data_filter']);}
            $paid_method = 'Unknown';
            if(isset($c['paid_method']) && !empty($c['paid_method'])){$paid_method = ucwords($c['paid_method']);}

            $record_print .= '
                <a href="subscriber_update.html?id='.$c['subscriber_id'].'" title="Edit subscriber" '.$pending.'>
                    <div class="item">'.$business_name.'</div>
                    <div class="item">'.$delivery_email.'</div>
                    <div class="item">'.$contact_name.'</div>
                    <div class="item">'.$subscribed_product_print.'</div>
                    <div class="item">'.$product_delivery_interval.'</div>
                    <div class="item">'.$product_data_filter.'</div>
                    <div class="item">'.$paid_method.'</div>
                    <div class="item">'.$paid_date.'</div>
                    <div class="item">'.$start_date.'</div>
                    <div class="item">'.$expiry_date.'</div>
                </a>
            ';
        }

        //complete the print
        $record_print .= '</div>';
    } else {
        $response = 'Error: Subscribers could not be found, please try again.';
    }
} else {
    $response = 'Error: '.$all_subscribers['response'];
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
define('PAGE', 'Subscriber List');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', '');

//site author
define('AUTHOR', 'raremedia pty ltd');

//define the individual page styles (you can link or write inline) - delimiter: RETURN
define('STYLES', '
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/foundation.min.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/typography.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/base.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/navigation.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/subscriber.page.css" />
');
//define the individual page javascript that runs at the start of the page - delimiter: RETURN
define('HEAD_JS', '');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// FOOT DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the individual page javascript that runs at the end of the page - delimiter: RETURN
define('FOOT_JS', '
    <script defer type="text/javascript" src="'.LOCAL.'web/script/jquery-3.2.1.min.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/form_validator.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/foundation.min.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/what-input.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/subscriber.page.js"></script>
');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(LOCAL.'secure/page/subscriber.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
