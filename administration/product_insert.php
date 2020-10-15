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
    'upload',
);

//name the site classes you need in scope
$required_classes = array(
    'auth',
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

//set container for insert completion context
$product_id = 0;
$state_fields = array();
$state_values = array();
$postcode_fields = array();
$state_field_select = '<option selected="selected">State data couldn\'t be found</option>';
$postcode_field_select = '<option selected="selected">Postcode data couldn\'t be found</option>';
$state_onceoff_price = '<h3 class="sub-heading gray-darker">State data couldn\'t be found</h3>';
$state_quarterly_price = '<h3 class="sub-heading gray-darker">State data couldn\'t be found</h3>';
$state_monthly_price = '<h3 class="sub-heading gray-darker">State data couldn\'t be found</h3>';
$step = 1;

//listen for post request and create product
if (!empty($_POST['action']) && $_POST['action'] === 'step_one') {

    //attempt to insert the product data
    $insert = $product->insert();
    if($insert['boolean']){

        //success message breaks JS
        //$response = 'Success: '.$insert['response'];

        //store values and go to step two
        $product_id = $insert['product_id'];
        $state_fields = $insert['state_fields'];
        $state_values = $insert['state_values'];
        $postcode_fields = $insert['postcode_fields'];
        $step = 2;

        //set field filter select for state
        if(!empty($state_fields)){
            $state_field_select = '<option>Select state data columns</option>';
            foreach($state_fields as $s){
                $state_field_select .= '<option value="'.$s.'">'.$s.'</option>';
            }
        }

        //set value pricing for state
        if(!empty($state_values)){
            $state_onceoff_price = '';
            $state_quarterly_price = '';
            $state_monthly_price = '';
            foreach($state_values as $s){
                $state_onceoff_price .= '
                    <h3 class="sub-heading gray-darker">'.strtoupper($s).'</h3>
                    <label for="'.$s.'OnceOffPrice">
                        Once off price
                        <input type="text" placeholder="$" id="'.$s.'OnceOffPrice" name="price[state][onceoff]['.$s.']" pattern="^[+-]?\d+(\.\d+)?$" autocomplete="off" />
                    </label>
                ';
                $state_quarterly_price .= '
                    <h3 class="sub-heading gray-darker">'.strtoupper($s).'</h3>
                    <label for="'.$s.'QuarterlyPrice">
                        Quarterly price
                        <input type="text" placeholder="$" id="'.$s.'QuarterlyPrice" name="price[state][quarterly]['.$s.']" pattern="^[+-]?\d+(\.\d+)?$" autocomplete="off" />
                    </label>
                ';
                $state_monthly_price .= '
                    <h3 class="sub-heading gray-darker">'.strtoupper($s).'</h3>
                    <label for="'.$s.'MonthlyPrice">
                        Monthly price
                        <input type="text" placeholder="$" id="'.$s.'MonthlyPrice" name="price[state][monthly]['.$s.']" pattern="^[+-]?\d+(\.\d+)?$" autocomplete="off" />
                    </label>
                ';
            }
        }

        //set field filter select for postcode
        if(!empty($postcode_fields)){
            $postcode_field_select = '<option>Select postcode data columns</option>';
            foreach($postcode_fields as $p){
                $postcode_field_select .= '<option value="'.$p.'">'.$p.'</option>';
            }
        }

    }else{
        $response = 'Error: '.$insert['response'];
    }
}

//listen for post request and complete the product
if (!empty($_POST['action']) && $_POST['action'] === 'step_two') {

    //complete the product record data and revert to step one
    $complete = $product->complete_insert();
    if($complete['boolean']){
        $response = 'Success: '.$complete['response'];
    }else{
        $response = 'Error: '.$complete['response'];
    }

    $step = 1;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// INCLUDE DEFINITIONS /////////////////////////////////////////////////////////////////////////////////////////////

//head include
define('HEAD', LOCAL . 'secure/include/head.include.php');

//foot include
define('FOOT', LOCAL . 'secure/include/foot.include.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// HEAD DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the name of the individual page  - delimiter: N/A
define('PAGE', 'Add Product');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Add Product');

//site author
define('AUTHOR', 'raremedia pty ltd');

//define the individual page styles (you can link or write inline) - delimiter: RETURN
define('STYLES', '
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/foundation.min.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/typography.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/base.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/navigation.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/product_insert.page.css" />
');

//define the individual page javascript that runs at the start of the page - delimiter: RETURN
define('HEAD_JS', '
');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// FOOT DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the individual page javascript that runs at the end of the page - delimiter: RETURN
define('FOOT_JS', '
    <script defer type="text/javascript" src="'.LOCAL.'web/script/jquery-3.2.1.min.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/form_validator.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/ckeditor/ckeditor.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/foundation.min.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/what-input.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/tag-box.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/product_insert.page.js"></script>
');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(LOCAL . 'secure/page/product_insert.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
