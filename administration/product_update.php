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

//set default print vars
$product_id = 0;
$state_fields = array();
$state_values = array();
$postcode_fields = array();
$state_field_select = '<option selected="selected">State data couldn\'t be found</option>';
$postcode_field_select = '<option selected="selected">Postcode data couldn\'t be found</option>';
$state_onceoff_price = '<h3 class="sub-heading gray-darker">State data couldn\'t be found</h3>';
$state_quarterly_price = '<h3 class="sub-heading gray-darker">State data couldn\'t be found</h3>';
$state_monthly_price = '<h3 class="sub-heading gray-darker">State data couldn\'t be found</h3>';
$state_haystack_active = '';
$postcode_haystack_active = '';

//make sure an update id is specified
if (isset($_GET['id']) && !empty($_GET['id']) || $_GET['id'] > 0) {

    //if post data is set
    if(isset($_POST) && !empty($_POST)){

        //attempt to update the product
        $update = $product->update($_GET['id']);
        if($update['boolean']){
            $response = 'Success: '.$update['response'];
        }else{
            $response = 'Error: '.$update['response'];
        }
    }

    //get product record
    $product_data = $product->get_by_id($_GET['id']);
    if (!$product_data['boolean'] || empty($product_data['content'])) {
        $response = 'Error: Product couldn\'t be found, please try again';
    }else{

        //check if we're processing a download request
        if(isset($_GET['download']) && $_GET['download'] === 'existing'){

            //attempt to create and header out csv
            $file_name = trim(str_replace(' ', '_', strtolower($product_data['content']['name']))).'_'.trim(str_replace(' ', '_', strtolower($product_data['content']['sku']))).'_' . date('dmY') . '.csv';
            $csv_export = $product->download_paid_tier_data($product_data['content']['product_id'], $file_name);
            if(!$csv_export['boolean']){
                $response = 'Error: '.$csv_export['response'];
            }
        }

        //set state and postcode filters ready for JS to read
        $state_haystack_active = implode(',', (array)json_decode($product_data['content']['state_haystack_active'])).',';
        $postcode_haystack_active = implode(',', (array)json_decode($product_data['content']['postcode_haystack_active'])).',';

        //store values
        $product_id = $product_data['content']['product_id'];
        $state_fields = (array)json_decode($product_data['content']['state_haystack_exist']);
        $postcode_fields = (array)json_decode($product_data['content']['postcode_haystack_exist']);

        //set field filter select for state
        if(!empty($state_fields)){
            $state_field_select = '<option>Select state data columns</option>';
            foreach($state_fields as $s){
                $state_field_select .= '<option value="'.$s.'">'.$s.'</option>';
            }
        }

        //set field filter select for postcode
        if(!empty($postcode_fields)){
            $postcode_field_select = '<option>Select postcode data columns</option>';
            foreach($postcode_fields as $p){
                $postcode_field_select .= '<option value="'.$p.'">'.$p.'</option>';
            }
        }

        //get state price array from product data
        $state_onceoff_price_array = (array)json_decode($product_data['content']['state_onceoff_price']);
        $state_quarterly_price_array = (array)json_decode($product_data['content']['state_quarterly_price']);
        $state_monthly_price_array = (array)json_decode($product_data['content']['state_monthly_price']);

        //build onceoff state pricing ui
        if(!empty($state_onceoff_price_array)){
            $state_onceoff_price = '';

            foreach($state_onceoff_price_array as $k => $v){
                $state_onceoff_price .= '
                    <h3 class="sub-heading gray-darker">'.strtoupper($k).'</h3>
                    <label for="'.$k.'OnceOffPrice">
                        Once off price
                        <input type="text" placeholder="$" id="'.$k.'OnceOffPrice" name="price[state][onceoff]['.$k.']" value="'.sprintf("%01.2f", $v).'" pattern="^[+-]?\d+(\.\d+)?$" autocomplete="off" />
                    </label>
                ';
            }
        }

        //build quarterly state pricing ui
        if(!empty($state_quarterly_price_array)){
            $state_quarterly_price = '';

            foreach($state_quarterly_price_array as $k => $v){
                $state_quarterly_price .= '
                    <h3 class="sub-heading gray-darker">'.strtoupper($k).'</h3>
                    <label for="'.$k.'QuarterlyPrice">
                        Quarterly price
                        <input type="text" placeholder="$" id="'.$k.'QuarterlyPrice" name="price[state][quarterly]['.$k.']" value="'.sprintf("%01.2f", $v).'" pattern="^[+-]?\d+(\.\d+)?$" autocomplete="off" />
                    </label>
                ';
            }
        }

        //build monthly state pricing ui
        if(!empty($state_monthly_price_array)){
            $state_monthly_price = '';

            foreach($state_monthly_price_array as $k => $v){
                $state_monthly_price .= '
                    <h3 class="sub-heading gray-darker">'.strtoupper($k).'</h3>
                    <label for="'.$k.'MonthlyPrice">
                        Monthly price
                        <input type="text" placeholder="$" id="'.$k.'MonthlyPrice" name="price[state][monthly]['.$k.']" value="'.sprintf("%01.2f", $v).'" pattern="^[+-]?\d+(\.\d+)?$" autocomplete="off" />
                    </label>
                ';
            }
        }
    }

}else{
    //send back to user list
    header('Location: '.LOCAL.'product.html');
    die();
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
define('PAGE', 'Update Product');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Update Product');

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
    <script type="text/javascript">
        var $product_id = '.$product_id.';
    </script>
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
    <script defer type="text/javascript" src="'.LOCAL.'web/script/resumable.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/tag-box.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/product_update.page.js"></script>
    </script>
');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(LOCAL . 'secure/page/product_update.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
