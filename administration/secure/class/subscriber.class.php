<?php
///////////////////////////////////////////////////////////////////////////////////
// Subscriber Class
// Site: postcode.auspost.com.au
// Purpose: Manage subscriptions
// Version 0.0.1
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
///////////////////////////////////////////////////////////////////////////////////

class subscriber
{

    //count the number of pending EFT subscriptions
    public function count_pending()
    {
        global $mysqli_db;

        $return = 0;

        //pending EFT subscribers
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `paid_time` = 0 AND `paid_method` = "eft" AND `manually_terminated` = 0';
        $return = $mysqli_db->raw_query($query_string, false)[0]['count'];

        return $return;
    }


    //select single subscriber by id
    public function get_by_id($id)
    {
        global $mysqli_db, $validate;

        $return = array(
            'boolean' => false,
            'response' => '',
            'content' => '',
        );

        //validate id
        if (!empty($id) && $validate->sanitise_handler($id, 'int', false)) {
            $mysqli_db->where('subscriber_id', $id);
            $select = $mysqli_db->get('subscriber', 1);

            if (!empty($select)) {
                $return['boolean'] = true;
                $return['content'] = $select;
            } else {
                $return['response'] = 'Subscriber could not be found, please try again';
            }
        } else {
            $return['response'] = 'Subscriber could not be found, please try again';
        }

        return $return;
    }


    //select subscribers with filter options
    public function get_search($keyword = '', $field = 'all', $expired = 0)
    {
        global $mysqli_db, $validate;

        $return = array(
            'boolean' => false,
            'response' => '',
            'content' => '',
        );

        //sanitise keyword
        if (!empty($keyword)) {
            $keyword = $validate->sanitise_handler($keyword, 'string', false);
            $keyword = $mysqli_db->escape($keyword);
        }

        //sanitise field
        if (!empty($field)) {
            $field = $validate->sanitise_handler($field, 'string', false);
            $field = $mysqli_db->escape($field);
        }

        //start query string
        $query_string = 'SELECT * FROM `subscriber` WHERE (`paid_time` > 0 OR paid_method = "eft") AND ';

        //set filter array
        $filters = array();

        //check expired and set filters
        if ($expired === 1) {

            //where expired time less than current time and not manually terminated
            $query_string .= '(`expiry_time` < ' . time() . ' OR `manually_terminated` > 0) AND (';
        } else {

            //where expired time less than current time
            $query_string .= '(`expiry_time` > ' . time() . ' AND `manually_terminated` = 0) AND (';
        }

        //paid reference search filter
        if ($field === 'paid_reference' || $field === 'all') {

            //where paid reference like
            $filters[] = '`paid_reference` LIKE "%' . $keyword . '%"';
        }

        //email address search filter
        if ($field === 'email_address' || $field === 'all') {

            //where delivery email address like
            $filters[] = '`delivery_email` LIKE "%' . strtolower($keyword) . '%"';

            //where support email address like
            $filters[] = '`support_email` LIKE "%' . strtolower($keyword) . '%"';
        }

        //business name search filter
        if ($field === 'business_name' || $field === 'all') {

            //where business name like
            $filters[] = '`business_name` LIKE "%' . $keyword . '%"';
        }

        //business abn search filter
        if ($field === 'business_abn' || $field === 'all') {

            //where business name like
            $filters[] = '`business_abn` LIKE "%' . $keyword . '%"';
        }

        //contact name search filter
        if ($field === 'contact_name' || $field === 'all') {

            //where business name like
            $filters[] = '`contact_name` LIKE "%' . $keyword . '%"';
        }

        //if filters are set add them, if not run standard query
        if (!empty($filters)) {
            $i = 0;
            foreach ($filters as $f) {
                if ($i === 0) {
                    $query_string .= $f;
                } else {
                    $query_string .= ' OR ' . $f;
                }

                $i++;
            }

            $query_string .= ') ORDER BY FIELD(`paid_time`, 0) DESC, `subscriber_id` DESC';
        } else {
            $query_string = substr($query_string, 0, -5);
            $query_string .= 'ORDER BY FIELD(`paid_time`, 0) DESC, `subscriber_id` DESC';
        }

        $select = $mysqli_db->raw_query($query_string, false);

        if (!empty($select)) {
            $return['boolean'] = true;
            $return['content'] = $select;
        } else {
            if (!empty($filters)) {
                $return['response'] = 'Subscribers matching your search parameters could not be found, please try again';
            } else {
                $return['response'] = 'Subscribers could not be found, please try again';
            }
        }

        return $return;
    }


    //select product download history by subsriber_id
    public function get_download_history($id)
    {
        global $mysqli_db, $validate;

        $return = array(
            'boolean' => false,
            'response' => '',
            'content' => '',
        );

        //validate id
        if (!empty($id) && $validate->sanitise_handler($id, 'int', false)) {
            $mysqli_db->where('subscriber', $id);
            $select = $mysqli_db->get('product_download_history');

            if (!empty($select)) {
                $return['boolean'] = true;
                $return['content'] = $select;
            } else {
                $return['response'] = 'Subscriber doesn\'t have a download history';
            }
        } else {
            $return['response'] = 'Download history couldn\'t be found';
        }

        return $return;
    }


    //select a download link by subscriber id
    public function get_link_by_subscriber($id)
    {
        global $mysqli_db, $validate;

        $return = array(
            'boolean' => false,
            'response' => '',
            'content' => '',
        );

        //validate id
        if (!empty($id) && $validate->sanitise_handler($id, 'int', false)) {
            $mysqli_db->where('subscriber_link_id', $id);
            $select = $mysqli_db->get('subscriber_link', 1);

            if (!empty($select)) {
                $return['boolean'] = true;
                $return['content'] = $select;
            } else {
                $return['response'] = 'Link could not be found, please try again';
            }
        } else {
            $return['response'] = 'Link could not be found, please try again';
        }

        return $return;
    }


    //create a paid tier download link
    public function create_link($subscriber_data, $update_delivery_time = true)
    {
        global $mysqli_db, $validate, $shortcut;

        $return = array(
            'boolean' => false,
            'response' => 'Download link couldn\'t be generated',
            'content' => '',
        );

        //build data array
        $data_array = array();
        $data_array['insert_time'] = time();
        $data_array['subscriber'] = $subscriber_data['subscriber_id'];
        $data_array['product'] = $subscriber_data['product'];
        $data_array['bookmark'] = $shortcut->random_string(8);

        //set data types
        $type_array = array();
        $type_array['insert_time'] = 'int';
        $type_array['subscriber'] = 'int';
        $type_array['product'] = 'int';
        $type_array['bookmark'] = 'string';

        //validate data
        $check = $validate->sanitise_array($data_array, $type_array, true); //validate and allow blank/zero
        if (!$check) {
            return $return;
        }

        //update delivery time on subscriber record
        if ($update_delivery_time) {
            //update delivery time on subscriber record
            $time_update = $mysqli_db->raw_query('UPDATE `subscriber` SET `update_time` = ' . time() . ', `last_delivery_time` = ' . time() . ' WHERE `subscriber_id` = ' . $subscriber_data['subscriber_id'], false);
        }

        //insert link into db
        $insert = $mysqli_db->insert('subscriber_link', $data_array);

        //if insert and update succeeded
        if ($insert) {
            $return['boolean'] = true;
            $return['content'] = $data_array['bookmark'];
            return $return;
        }

        return $return;
    }


    //approve or deny pending eft subscriber
    public function eft_approve($id, $approve = 'deny')
    {
        global $mysqli_db, $validate, $product, $email;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to confirm your subscriber',
        );

        if (empty($id) || !$validate->sanitise_handler($id, 'int', false)) {
            return $return;
        }

        //make sure the referrer is correct
        if (!$validate->check_referer()) {
            return $return;
        }

        //find subscription record
        $subscriber_select = $this->get_by_id($id);
        if (!$subscriber_select['boolean']) {
            return $return;
        }

        //find subscribed product
        $product_select = $product->get_by_id($subscriber_select['content']['product']);
        if (!$product_select['boolean']) {
            return $return;
        }

        //set paid and expiry time
        $paid_time = time();
        $expiry_time = strtotime('+'.(int)$product_select['content']['subscription_term'].' months', $paid_time);

        //are we denying or approving the subscriber
        if ($approve === 'allow') {

            //set update data
            $update_data = array();
            $update_data['update_time'] = time();
            $update_data['last_delivery_time'] = time();
            $update_data['paid_method'] = 'eft';
            $update_data['paid_time'] = $paid_time;
            $update_data['expiry_time'] = $expiry_time;

            //set validation types
            $type_array = array();
            $type_array['update_time'] = 'int';
            $type_array['last_delivery_time'] = 'int';
            $type_array['paid_method'] = 'string';
            $type_array['paid_time'] = 'int';
            $type_array['expiry_time'] = 'int';

            //validate data
            $check = $validate->sanitise_array($update_data, $type_array, true); //validate and allow blank/zero
            if (!$check) {
                return $return;
            }
        } else {

            //set update data
            $update_data = array();
            $update_data['update_time'] = time();
            $update_data['paid_method'] = 'eft';
            $update_data['manually_terminated'] = time();

            //set validation types
            $type_array = array();
            $type_array['update_time'] = 'int';
            $type_array['paid_method'] = 'string';
            $type_array['manually_terminated'] = 'int';

            //validate data
            $check = $validate->sanitise_array($update_data, $type_array, true); //validate and allow blank/zero
            if (!$check) {
                return $return;
            }
        }

        //run the update request
        $mysqli_db->where('subscriber_id', $subscriber_select['content']['subscriber_id']);
        $update = $mysqli_db->update('subscriber', $update_data);
        if (!$update) {
            return $return;
        }

        $return['boolean'] = true;
        if ($approve === 'allow') {

            //send invoice email to subscriber
            if (!$email->send_invoice($subscriber_select['content'], $product_select['content'])) {
                return $return;
            }

            //create paid tier download link
            $link = $this->create_link($subscriber_select['content']);
            if (!$link['boolean']) {
                return $return;
            }

            //send download delivery email to subscriber
            if (!$email->send_link_download($subscriber_select['content'], $product_select['content'], $link['content'])) {
                return false;
            }

            $return['response'] = 'Your subscriber has been approved';
        } else {
            $return['response'] = 'Your subscriber has been terminated';
        }

        return $return;
    }


    //re-send a product data download link to subscriber
    public function resend_download_link($id, $alt_email = '')
    {
        global $mysqli_db, $validate, $product, $email;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to resend a download link',
        );

        if (empty($id) || !$validate->sanitise_handler($id, 'int', false)) {
            return $return;
        }

        //make sure the referrer is correct
        if (!$validate->check_referer()) {
            return $return;
        }

        //find subscription record
        $subscriber_select = $this->get_by_id($id);
        if (!$subscriber_select['boolean']) {
            return $return;
        }

        //find subscribed product
        $product_select = $product->get_by_id($subscriber_select['content']['product']);
        if (!$product_select['boolean']) {
            return $return;
        }

        //create paid tier download link without updating last delivery time
        $link = $this->create_link($subscriber_select['content'], false);
        if (!$link['boolean']) {
            $return['response'] = $link['response'];
            return $return;
        }

        //send download delivery email to subscriber
        if (!$email->send_link_download($subscriber_select['content'], $product_select['content'], $link['content'], $alt_email)) {
            return $return;
        }

        $return['boolean'] = true;
        $return['response'] = 'A download link has been resent to your subscriber';
        return $return;
    }


    //immediately terminate a subscriber
    public function terminate($id)
    {
        global $mysqli_db, $validate, $product;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to terminate your subscriber',
        );

        if (empty($id) || !$validate->sanitise_handler($id, 'int', false)) {
            return $return;
        }

        //make sure the referrer is correct
        if (!$validate->check_referer()) {
            return $return;
        }

        //set update data
        $update_data = array();
        $update_data['update_time'] = time();
        $update_data['manually_terminated'] = time();

        //set validation types
        $type_array = array();
        $type_array['update_time'] = 'int';
        $type_array['manually_terminated'] = 'int';

        //validate data
        $check = $validate->sanitise_array($update_data, $type_array, true); //validate and allow blank/zero
        if (!$check) {
            return $return;
        }

        //run the update request
        $mysqli_db->where('subscriber_id', $id);
        $update = $mysqli_db->update('subscriber', $update_data);
        if (!$update) {
            return $return;
        }

        $return['boolean'] = true;
        $return['response'] = 'Your subscriber has been terminated';
        return $return;
    }


    //manually create a subscriber
    public function insert()
    {
        global $validate, $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to save your subscriber, please try again',
            'subscriber_id' => 0,
        );

        //make sure the referrer is correct
        if (!$validate->check_referer()) {
            return $return;
        }

        //make sure post fields are set
        if (
            !isset($_POST['business_name']) ||
            !isset($_POST['business_address']) ||
            !isset($_POST['business_suburb']) ||
            !isset($_POST['business_state']) ||
            !isset($_POST['business_postcode']) ||
            !isset($_POST['contact_name']) ||
            !isset($_POST['contact_phone']) ||
            !isset($_POST['delivery_email']) ||
            !isset($_POST['product']) ||
            !isset($_POST['paid_time']) ||
            !isset($_POST['expiry_time']) ||
            !isset($_POST['filter']) ||
            !isset($_POST['paid_method']) ||
            !isset($_POST['paid_total']) ||
            !isset($_POST['interval']) ||
            empty($_POST['business_name']) ||
            empty($_POST['business_address']) ||
            empty($_POST['business_suburb']) ||
            empty($_POST['business_state']) ||
            empty($_POST['business_postcode']) ||
            empty($_POST['contact_name']) ||
            empty($_POST['contact_phone']) ||
            empty($_POST['delivery_email']) ||
            empty($_POST['product']) ||
            empty($_POST['paid_time']) ||
            empty($_POST['expiry_time']) ||
            empty($_POST['filter']) ||
            empty($_POST['paid_method']) ||
            empty($_POST['paid_total']) ||
            empty($_POST['interval'])
        ) {
            $return['response'] = 'Please make sure you\'ve completed the required fields';
            return $return;
        }

        //set container for product insert data
        $insert_data = array();

        //define insert data
        $insert_data['insert_time'] = time();
        $insert_data['modified_by'] = $_SESSION['user_id'];
        $insert_data['business_name'] = $_POST['business_name'];
        $insert_data['business_address'] = $_POST['business_address'];
        $insert_data['business_suburb'] = $_POST['business_suburb'];
        $insert_data['business_state'] = $_POST['business_state'];
        $insert_data['business_postcode'] = $_POST['business_postcode'];
        $insert_data['business_department'] = $_POST['business_department'];
        $insert_data['contact_name'] = $_POST['contact_name'];
        $insert_data['contact_phone'] = $_POST['contact_phone'];
        $insert_data['delivery_email'] = $_POST['delivery_email'];
        $insert_data['support_email'] = $_POST['support_email'];
        $insert_data['business_abn'] = $_POST['business_abn'];
        $insert_data['product'] = $_POST['product'];
        $insert_data['paid_time'] = strtotime(str_replace('/', '-', $_POST['paid_time']));
        $insert_data['expiry_time'] = strtotime(str_replace('/', '-', $_POST['expiry_time']));
        $insert_data['paid_method'] = strtolower($_POST['paid_method']);
        $insert_data['paid_total'] = $_POST['paid_total'];
        $insert_data['paid_reference'] = $_POST['paid_reference'];

        $insert_data['reseller'] = 0;
        if (isset($_POST['reseller']) && $_POST['reseller'] === 'on') {
            $insert_data['reseller'] = 1;
        }

        $insert_data['product_delivery_interval'] = strtolower($_POST['interval']);
        $insert_data['product_data_filter'] = strtolower($_POST['filter']);

        //make sure the filter value is acceptable
        if ($insert_data['product_delivery_interval'] !== "onceoff" && $insert_data['product_delivery_interval'] !== "quarterly" && $insert_data['product_delivery_interval'] !== "monthly") {
            $return['response'] = 'Please choose a valid product delivery interval - once off, quarterly or monthly';
            return $return;
        }

        //make sure the filter value is acceptable
        if ($insert_data['product_data_filter'] !== "national" && $insert_data['product_data_filter'] !== "state" && $insert_data['product_data_filter'] !== "postcode") {
            $return['response'] = 'Please choose a valid product filter - national, state or postcode';
            return $return;
        }

        //don't process filter needles for national purchases
        if ($insert_data['product_data_filter'] !== "national") {
            $insert_data['product_data_needle'] = array_values(array_filter(explode(',', $_POST['needle']), 'strlen'));
            if (!empty($insert_data['product_data_needle'])) {
                $insert_data['product_data_needle'] = json_encode($insert_data['product_data_needle']);
            } else {
                $insert_data['product_data_needle'] = '';
            }
        } else {
            $insert_data['product_data_needle'] = '';
        }

        //set container for validation types
        $type_array = array();

        $type_array['insert_time'] = 'int';
        $type_array['modified_by'] = 'int';
        $type_array['business_name'] = 'string';
        $type_array['business_address'] = 'string';
        $type_array['business_suburb'] = 'string';
        $type_array['business_state'] = 'string';
        $type_array['business_postcode'] = 'string';
        $type_array['business_department'] = 'string';
        $type_array['contact_name'] = 'string';
        $type_array['contact_phone'] = 'string';
        $type_array['delivery_email'] = 'email';
        $type_array['support_email'] = 'email';
        $type_array['business_abn'] = 'string';
        $type_array['product'] = 'int';
        $type_array['paid_time'] = 'int';
        $type_array['expiry_time'] = 'int';
        $type_array['product_data_filter'] = 'string';
        $type_array['paid_method'] = 'string';
        $type_array['paid_total'] = 'float';
        $type_array['paid_reference'] = 'string';
        $type_array['reseller'] = 'int';
        $type_array['product_data_needle'] = 'string';
        $type_array['product_delivery_interval'] = 'string';

        //validate data
        $check = $validate->sanitise_array($insert_data, $type_array, true); //validate and allow blank/zero
        if (!$check) {
            $return['response'] = 'Please make sure you\'ve entered valid details into the required fields';
            return $return;
        }

        //run the insert request
        $insert = $mysqli_db->insert('subscriber', $insert_data);
        if (!$insert) {
            return $return;
        }

        //return the product id
        $return['subscriber_id'] = $insert;
        $return['boolean'] = true;
        $return['response'] = 'Your subscriber has been saved';
        return $return;
    }


    //update subscriber
    public function update($id)
    {
        global $mysqli_db, $validate;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to save your subscriber, please try again',
        );

        if (empty($id) || !$validate->sanitise_handler($id, 'int', false)) {
            return $return;
        }

        //make sure the referrer is correct
        if (!$validate->check_referer()) {
            return $return;
        }

        //make sure post fields are set
        if (
            !isset($_POST['business_name']) ||
            !isset($_POST['contact_name']) ||
            !isset($_POST['delivery_email']) ||
            empty($_POST['business_name']) ||
            empty($_POST['contact_name']) ||
            empty($_POST['delivery_email'])
        ) {
            $return['response'] = 'Please make sure you\'ve entered a business name, contact name and delivery email address';
            return $return;
        }

        //set container for update and validation
        $update_data = array();
        $type_array = array();


        $update_data['update_time'] = time();
        $update_data['modified_by'] = (int)$_SESSION['user_id'];

        $update_data['marketing_opt_in'] = 0;
        if (isset($_POST['marketing_opt_in']) && $_POST['marketing_opt_in'] === 'on') {
            $update_data['marketing_opt_in'] = 1;
        }
        $update_data['attach_payload'] = 0;
        if (isset($_POST['attach_payload']) && $_POST['attach_payload'] === 'on') {
            $update_data['attach_payload'] = 1;
        }
        $update_data['reseller'] = 0;
        if (isset($_POST['reseller']) && $_POST['reseller'] === 'on') {
            $update_data['reseller'] = 1;
        }

        if(isset($_POST['last_delivery_time']) && !empty($_POST['last_delivery_time'])){
            $update_data['last_delivery_time'] = strtotime(str_replace('/', '-', $_POST['last_delivery_time'])); //change date slashes to dashes so stringtotime understands
        }else{
            $update_data['last_delivery_time'] = 0;
        }

        //paid ref is a disabled field for credit orders, can only be updated for eft
        if(isset($_POST['paid_reference']) && !empty($_POST['paid_reference'])){
            $update_data['paid_reference'] = $_POST['paid_reference'];
            $type_array['paid_reference'] = 'string';
        }

        $update_data['business_name'] = $_POST['business_name'];
        $update_data['business_address'] = $_POST['business_address'];
        $update_data['business_suburb'] = $_POST['business_suburb'];
        $update_data['business_state'] = $_POST['business_state'];
        $update_data['business_postcode'] = $_POST['business_postcode'];
        $update_data['business_department'] = $_POST['business_department'];
        $update_data['contact_name'] = $_POST['contact_name'];
        $update_data['contact_phone'] = $_POST['contact_phone'];
        $update_data['delivery_email'] = $_POST['delivery_email'];
        $update_data['support_email'] = $_POST['support_email'];
        $update_data['business_abn'] = $_POST['business_abn'];

        $type_array['update_time'] = 'int';
        $type_array['modified_by'] = 'int';
        $type_array['marketing_opt_in'] = 'int';
        $type_array['attach_payload'] = 'int';
        $type_array['reseller'] = 'int';
        $type_array['last_delivery_time'] = 'int';
        $type_array['business_name'] = 'string';
        $type_array['business_address'] = 'string';
        $type_array['business_suburb'] = 'string';
        $type_array['business_state'] = 'string';
        $type_array['business_postcode'] = 'string';
        $type_array['business_department'] = 'string';
        $type_array['contact_name'] = 'string';
        $type_array['contact_phone'] = 'string';
        $type_array['delivery_email'] = 'email';
        $type_array['support_email'] = 'email';
        $type_array['business_abn'] = 'string';

        //validate data
        $check = $validate->sanitise_array($update_data, $type_array, true); //validate and allow blank/zero
        if (!$check) {
            $return['response'] = 'Please make sure you\'ve entered valid details into the required fields';
            return $return;
        }

        //run the update request
        $mysqli_db->where('subscriber_id', $id);
        $update = $mysqli_db->update('subscriber', $update_data);
        if (!$update) {
            return $return;
        }

        $return['boolean'] = true;
        $return['response'] = 'Your subscriber has been saved';
        return $return;
    }


    //generate subscriber invoice as pdf and download
    public function download_invoice($id)
    {
        global $mysqli_db, $validate, $product;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to generate your invoice',
        );

        if (empty($id) || !$validate->sanitise_handler($id, 'int', false)) {
            return $return;
        }

        //find subscription record
        $subscriber_select = $this->get_by_id($id);
        if (!$subscriber_select['boolean']) {
            return $return;
        }

        //find subscribed product
        $product_select = $product->get_by_id($subscriber_select['content']['product']);
        if (!$product_select['boolean']) {
            return $return;
        }

        //instantiate pdf library
        require_once(ROOT . 'secure/library/fpdf/fpdf.php');
        $pdf = new fpdf();

        //set file and page name
        $file_name = $subscriber_select['content']['subscriber_id'].'_subscriber_invoice_'.date('dmY').'.pdf';
        $pdf->SetTitle($file_name);

        //configure page
        $borders = 0; //set to 1 to see borders
        $pdf->AddPage(); //default page
        $pdf->SetMargins(24, 16, 24);
        $page_top = $pdf->tMargin;
        $page_left = $pdf->lMargin;
        $page_right = $pdf->rMargin;
        $content_width = $pdf->w - $pdf->lMargin - $pdf->rMargin;

        //logo
        $pdf->Image(ROOT . 'web/image/email/auspost-mail-logo.png', $page_left, $page_top, 0, 0, 'PNG');

        //page title
        $pdf->Ln(30);
        $pdf->SetTextColor(220, 25, 40);
        $pdf->SetFont('Arial', '', 24);
        $pdf->Cell(0, 0, 'Subscription tax invoice', $borders, 0, 'L', 0);

        //reset font
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);

        //text content
        $pdf->Ln(16);
        $pdf->Cell(0, 0, 'Dear '.$subscriber_select['content']['contact_name'].',', $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Thank you for purchasing '.$product_select['content']['name'].' for '.$product_select['content']['subscription_term'].' months.', $borders, 0, 'L', 0);

        //reset font
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(0, 0, 0);

        //text content
        $pdf->Ln(10);
        $pdf->Cell(0, 0, 'Subscription Summary & Tax Invoice', $borders, 0, 'L', 0);

        //reset font
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Product: '.$product_select['content']['name'].' ('.$product_select['content']['sku'].')', $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Term of use: '.$product_select['content']['subscription_term'].' months', $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Update frequency:	'.ucwords($subscriber_select['content']['product_delivery_interval']), $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Coverage:	'.ucwords($subscriber_select['content']['product_data_filter']), $borders, 0, 'L', 0);

        if ($subscriber_select['content']['product_data_filter'] !== 'national') {
            //text content
            $pdf->Ln(8);
            $pdf->Cell(0, 0, 'Selected: '.implode(' / ', (array)json_decode($subscriber_select['content']['product_data_needle'])), $borders, 0, 'L', 0);
        }

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Invoice Number: '.$subscriber_select['content']['subscriber_id'], $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Price: $'.number_format($subscriber_select['content']['paid_total'], 2), $borders, 0, 'L', 0);

        //reset font
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(0, 0, 0);

        //text content
        $pdf->Ln(10);
        $pdf->Cell(0, 0, 'Your Details', $borders, 0, 'L', 0);

        //reset font
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Business name: '.$subscriber_select['content']['business_name'], $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Business address: '.$subscriber_select['content']['business_address'], $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Business suburb: '.$subscriber_select['content']['business_suburb'], $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Business state: '.strtoupper($subscriber_select['content']['business_state']), $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Business postcode: '.$subscriber_select['content']['business_postcode'], $borders, 0, 'L', 0);

        if (!empty($subscriber_select['content']['business_department'])) {
            //text content
            $pdf->Ln(8);
            $pdf->Cell(0, 0, 'Department: '.$subscriber_select['content']['business_department'], $borders, 0, 'L', 0);
        }

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Contact name: '.$subscriber_select['content']['contact_name'], $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Phone number: '.$subscriber_select['content']['contact_phone'], $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Delivery email address: '.$subscriber_select['content']['delivery_email'], $borders, 0, 'L', 0);

        if (!empty($subscriber_select['content']['support_email'])) {
            //text content
            $pdf->Ln(8);
            $pdf->Cell(0, 0, 'Support email address: '.$subscriber_select['content']['support_email'], $borders, 0, 'L', 0);
        }


        if (!empty($subscriber_select['content']['business_abn'])) {
            //text content
            $pdf->Ln(8);
            $pdf->Cell(0, 0, 'ABN/ACN: '.$subscriber_select['content']['business_abn'], $borders, 0, 'L', 0);
        }

        //reset font
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(0, 0, 0);

        //text content
        $pdf->Ln(10);
        $pdf->Cell(0, 0, 'Australian Postal Corporation', $borders, 0, 'L', 0);

        //reset font
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'ABN: 28 864 970 579', $borders, 0, 'L', 0);

        //text content
        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Level 2, 111 Bourke Street Melbourne VIC 3000', $borders, 0, 'L', 0);

        $pdf_dowload = 'D';
        if (ENVIRONMENT === 'local') {
            $pdf_dowload = 'I';
        }
        $pdf->Output('invoice.pdf', $pdf_dowload);
        die();
    }
}
