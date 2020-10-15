<?php
///////////////////////////////////////////////////////////////////////////////////
// Product Class
// Site: postcode.auspost.com.au
// Purpose: Manage subscription products
// Version 0.0.1
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
///////////////////////////////////////////////////////////////////////////////////

class product
{

    private $_csv_chunk_interval = 500; //how many data rows to pull when generating CSVs

    //select single product by id
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

            $mysqli_db->where('product_id', $id);
            $select = $mysqli_db->get('product', 1);

            if (!empty($select)) {
                $return['boolean'] = true;
                $return['content'] = $select;
            } else {
                $return['response'] = 'Product could not be found, please try again';
            }
        } else {
            $return['response'] = 'Product could not be found, please try again';
        }

        return $return;
    }


    //select free published products
    public function get_published_free_tier()
    {
        global $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => '',
            'content' => '',
        );

        $select = $mysqli_db->query('SELECT * FROM `product` WHERE `published` = 1 AND `enable_free_tier` = 1 AND `name` != "" AND `sku` != "" AND `subscription_term` != 0 ORDER BY `name` ASC');

        if (!empty($select)) {
            $return['boolean'] = true;
            $return['content'] = $select;
        } else {
            $return['response'] = 'No free products could be found, please add a product';
        }

        return $return;
    }


    //select published products
    public function get_published()
    {
        global $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => '',
            'content' => '',
        );

        $select = $mysqli_db->query('SELECT * FROM `product` WHERE `published` = 1 AND `name` != "" AND `sku` != "" AND `subscription_term` != 0 ORDER BY `name` ASC');

        if (!empty($select)) {
            $return['boolean'] = true;
            $return['content'] = $select;
        } else {
            $return['response'] = 'No paid products could be found, please add a product';
        }

        return $return;
    }


    //select all products
    public function get_all()
    {
        global $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => '',
            'content' => '',
        );

        $select = $mysqli_db->query('SELECT * FROM `product` WHERE `name` != "" AND `sku` != "" AND `subscription_term` != 0 ORDER BY `sku`, `name` ASC');

        if (!empty($select)) {
            $return['boolean'] = true;
            $return['content'] = $select;
        } else {
            $return['response'] = 'Products could not be found, please add a product';
        }

        return $return;
    }


    //set stream download headers
    private function download_headers($file_name) {

        //disable caching
        $now = gmdate("D, d M Y H:i:s");
        header('Pragma: public');
        header("Expires: 0");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        //force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        //disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$file_name}");
        header("Content-Transfer-Encoding: binary");
    }


    //download paid tier data
    public function download_paid_tier_data($id, $name)
    {
        global $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => 'An error occured while preparing your product data for download'
        );

        if(empty($id) || empty($name)){
            return $return;
        }

        //get column names from data table
        $column_data = $mysqli_db->raw_query('SELECT `column_name` FROM `information_schema`.`columns` WHERE `table_schema` = "'.DB_NAME.'" AND `table_name` = "product_data_'.$id.'"');
        if(empty($column_data)){
            return $return;
        }

        //set headers
        $this->download_headers($name);

        //open file stream
        $fh = @fopen('php://output', 'w');

        //add stream filters to convert \n (line feed) line endings to \r\n (carraige return, line feed)
        stream_filter_register('crlf', 'crlf_filter');
        stream_filter_append($fh, 'crlf');

        //set the csv column titles
        $i = 0;
        $columns = array();
        $header_row = array();
        foreach($column_data as $c){
                if($i > 1){
                    $header_row[] = $c['column_name'];
                }
                $columns[] = $c['column_name'];
                $i++;
        }

        //add header row to output
        fputcsv($fh, $header_row);

        //find our starting id and set first row id for recursion
        $query_string = 'SELECT `'.$columns[0].'` FROM `product_data_'.$id.'` ORDER BY `'.$columns[0].'` ASC LIMIT 1';
        $current_id = (int)$mysqli_db->raw_query($query_string, false)[0];

        //if we can't set a current id, there aren't any results
        if(empty($current_id) || !$current_id){

            //write to csv output
            fputcsv($fh, array('No results could be found, please try again.'));

            //close the file stream
            fclose($fh);
            die();
        }

        //start iterating
        while($current_id > 0){

            //retrieve single row from subscriber table
            $query_string = 'SELECT `'.implode('`,`', $columns).'`
                        FROM `product_data_'.$id.'`
                        WHERE `'.$columns[0].'` >= '.$current_id.'
                        ORDER BY `product_data_'.$id.'`.`'.$columns[0].'` ASC
                        LIMIT '.$this->_csv_chunk_interval;
            $chunk = $mysqli_db->raw_query($query_string, false);

            //make sure there's a result
            if(empty($chunk)){
                break;
            }

            foreach($chunk as $row){

                //update current id
                $current_id = $row[$columns[0]] + 1;

                //remove junk fields
                $junk = array_shift($row);
                $junk = array_shift($row);

                //write to csv output
                fputcsv($fh, $row);

                //recover memory
                unset($row);
            }

            //recover memory
            unset($query_string);
            unset($chunk);
        }

        //close the file stream
        fclose($fh);
        die();
    }


    //upload product file to S3
    private function upload_file($dir, $file)
    {
        global $upload;

        $return = array(
            'boolean' => false,
            'content' => ''
        );

        //destination of file dyn directory on S3
        $upload->set_destination(ENVIRONMENT.'/'.$dir);

        //define file to upload from $_FILES
        $upload->file($file);

        //limit the file size
        $upload->set_max_file_size(FILE_SIZE_LIMIT);

        //allow PDF and CSV only (CSV can be defined differently depending on OS, browser and the program they were created with)
        $upload->set_allowed_mime_types(
            array(
                'image/png', //png image
                'application/pdf', //pdf
                'application/vnd.ms-excel', //csv
                'text/plain', //csv (or any text file)
                'text/csv', //csv
                'text/tsv' //csv
            )
        );

        $upload_result = $upload->upload(); //upload the file

        //check if the upload was successful
        if ($upload_result['boolean']) {
            $return['boolean'] = true;
            $return['content'] = $upload_result['filename'];
        }

        return $return;
    }

    //translate paid tier file into product data table
    private function merge_paid_tier($product_id, $file, $append_replace = 'replace')
    {
        global $mysqli_db, $validate;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to save your product, please try again.',
            'state_fields' => array(),
            'state_values' => array(),
            'postcode_fields' => array(),
        );

        //make sure a valid data merging option has been set
        if($append_replace !== 'replace' && $append_replace !== 'append'){
            return $return;
        }

        //set container for data table name
        $table_name = 'product_data_'.$product_id;

        //product data table create closure
        $create_table_sql = function($table_name, $field_names)
        {
            global $mysqli_db;

            //set create statment
            $statement = '
                CREATE TABLE `'.$table_name.'` (
                  `'.$table_name.'_id` int(12) NOT NULL AUTO_INCREMENT,
                  `insert_time` int(12) NOT NULL,
                  `'.implode('` longtext COLLATE utf8_unicode_ci NOT NULL,
                  `', $field_names).'` longtext COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`'.$table_name.'_id`),
                  UNIQUE KEY `'.$table_name.'_id_UNIQUE` (`'.$table_name.'_id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
            ';

            return $mysqli_db->raw_query($statement);
        };

        //product data table create closure
        $insert_data_sql = function($table_name, $field_names, $row_data)
        {
            global $mysqli_db;

            //break the row data into blocks of 500
            $row_data = array_chunk($row_data, 500);

            //set insert time
            $insert_time = time();

            //loop through chunks and build insert statements
            foreach($row_data as $chunk){

                //set first part of insert statement
                $statement = '
                    INSERT INTO `'.$table_name.'` (
                    `insert_time`,
                    `'.implode('`, `', $field_names).'`
                    ) VALUES
                ';

                //loop through rows and populate current insert statement's values
                foreach($chunk as $r){
                    $statement .= '('.$insert_time.', "'.implode('", "', str_replace('"', '', $r)).'"),';
                }

                //clean up the statement string
                $statement = substr($statement, 0, -1);

                //run the insert query
                $insert = $mysqli_db->raw_query($statement);
                if(!$insert){
                    $return['response'] = 'Your paid tier data couldn\'t be saved';
                    return $return;
                }
            }

            return;
        };

        //array value wildcard search closure
        $array_value_wild = function($array, $search, $return = '')
        {
            $search = str_replace('\*', '.*?', preg_quote($search, '/'));
            $result = preg_grep('/^' . $search . '$/i', array_values($array));
            return $result;
        };

        //make a string safe for sql field name use
        $sql_field_name = function($string) {
            $string = str_replace(' ', '_', $string); //spaces to underscores
            $string = preg_replace('/[^\w-]/', '', $string); //alphanum and underscores only
            return preg_replace('/_+/', '_', $string); //multiple underscores to one underscore
        };

        //attempt to read the csv file
        $handle = fopen($file['tmp_name'], "r");
        if(!$handle){
            $return['response'] = 'The paid tier file uploaded couldn\'t be read';
            return $return;
        }

        //container for column titles
        $field_names = array();
        $row_data = array();

        //read csv content
        $i = 0;
        while (($data = fgetcsv($handle)) !== false) {

            //separate csv data
            if($i === 0){
                $field_names = $sql_field_name($data);
            }else{
                $row_data[] = $data;
            }

            $i++;
        }

        //close file handle
        fclose($handle);

        //fail if data rows weren't set
        if($i === 0 || empty($row_data)){
            $return['response'] = 'The paid tier file uploaded didn\'t appear to contain any data';
            return $return;
        }

        //if we're appending data or replacing data
        if($append_replace === 'append'){

            //check if data table exists
            $table_exists = $mysqli_db->raw_query('SELECT * FROM `information_schema`.`tables` WHERE `table_schema` = "'.DB_NAME.'" AND `table_name` = "'.$table_name.'" LIMIT 1');

            //if the table doesn't already exist, error and stop
            if(empty($table_exists)){
                $return['response'] = 'Previous data doesn\'t exist for this product, your paid tier data can\'t be merged into existing data';
                return $return;
            }

            //get column names from data table
            $column_select = $mysqli_db->raw_query('SELECT `column_name` FROM `information_schema`.`columns` WHERE `table_schema` = "'.DB_NAME.'" AND `table_name` = "'.$table_name.'"');
            $column_names = array();
            foreach($column_select as $c){
                $column_names[] = $c['column_name'];
            }
            if(empty($column_names)){
                $return['response'] = 'Your previous paid tier data couldn\'t be cross checked against your new paid tier data';
                return $return;
            }

            //make sure field names already exist in table
            foreach($field_names as $f){
                if(!in_array($f, $column_names)){
                    $return['response'] = 'Your new paid tier data contains columns that don\'t exist in the existing paid tier data';
                    return $return;
                }
            }

            //NOTE: product ID 5 is movers statistics, it should only retain the last 5 years of data
            if($product_id == 5){

                //set the age limit of each product data row
                $five_years_ago = strtotime('-5 years', time());

                //delete old data
                $mysqli_db->raw_query('DELETE FROM `'.$table_name.'` WHERE `insert_time` < '.$five_years_ago);
            }
        }else{

            //drop any existing data table
            $mysqli_db->raw_query('DROP TABLE IF EXISTS `'.$table_name.'`');

            //create product data table
            $create_table = $create_table_sql($table_name, $field_names);
            if(!$create_table){
                $return['response'] = 'Your paid tier data couldn\'t be saved';
                return $return;
            }
        }

        //insert row data
        $insert_data_sql($table_name, $field_names, $row_data);

        //find all possible state fields from data
        $return['state_fields'] = array_values($array_value_wild($field_names, '*state*'));

        //find all possible postcode fields from data
        $return['postcode_fields'] = array_values($array_value_wild($field_names, '*postcode*'));

        //find unique state values from state fields
        $unique_states = $mysqli_db->raw_query('(SELECT `'.implode('` as `state` FROM `'.$table_name.'` GROUP BY `state`) UNION (SELECT `', $return['state_fields']).'` as `state` FROM `'.$table_name.'` GROUP BY `state`)');
        if(!empty($unique_states)){

            //collate selected values to return
            foreach($unique_states as $s){
                $return['state_values'][] = $s['state'];
            }
        }

        //update state and postcode haystack fields on the product meta data
        //set container for product update data
        $update_data = array();
		$update_data['update_time'] = time();
		$update_data['modified_by'] = $_SESSION['user_id'];
        $update_data['state_haystack_exist'] = '';
        if(!empty($return['state_fields'])){
            $update_data['state_haystack_exist'] = json_encode($return['state_fields']);
        }
        $update_data['postcode_haystack_exist'] = '';
        if(!empty($return['postcode_fields'])){
            $update_data['postcode_haystack_exist'] = json_encode($return['postcode_fields']);
        }

        //set container for validation types
        $type_array = array();
		$type_array['update_time'] = 'int';
        $type_array['modified_by'] = 'int';
        $type_array['state_haystack_exist'] = 'string';
        $type_array['postcode_haystack_exist'] = 'string';

        //validate data
        $check = $validate->sanitise_array($update_data, $type_array, true); //validate and allow blank/zero
        if(!$check){
            return $return;
        }

        //run the update request
        $mysqli_db->where('product_id', $product_id);
        $update = $mysqli_db->update('product', $update_data);
        if(!$update){
            return $return;
        }

        //return success
        $return['boolean'] = true;
        $return['response'] = 'Your paid tier data has been saved';
        return $return;
    }


    //create a product record and return the id
    function insert()
    {
        global $validate, $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to save your product, please try again',
            'product_id' => 0,
            'field_names' => array()
        );

        //make sure the referrer is correct
        if (!$validate->check_referer()) {
            return $return;
        }

        //make sure post fields are set
        if (
            !isset($_POST['name']) ||
            !isset($_POST['sku']) ||
            !isset($_POST['subscription_term']) ||
            empty($_POST['name']) ||
            empty($_POST['sku']) ||
            empty($_POST['subscription_term'])
        ){
            $return['response'] = 'Please make sure you\'ve entered a product name, SKU and subscription term';
            return $return;
        }

        //set container for product insert data
        $insert_data = array();

        //make sure paid tier file is set
        if(
            !isset($_FILES['paid_tier_file']) ||
            $_FILES['paid_tier_file']['error'] !== 0 ||
            (
                $_FILES['paid_tier_file']['type'] !== 'application/octet-stream' &&
                $_FILES['paid_tier_file']['type'] !== 'application/vnd.ms-excel' &&
                $_FILES['paid_tier_file']['type'] !== 'text/plain' &&
                $_FILES['paid_tier_file']['type'] !== 'text/csv' &&
                $_FILES['paid_tier_file']['type'] !== 'text/tsv'
            )
        ){
            $return['response'] = 'Please make sure you\'ve chosen a paid tier file in CSV format';
            return $return;
        }

        //if free tier is enabled
        $insert_data['free_tier_file'] = '';
        if(isset($_POST['enable_free_tier']) && $_POST['enable_free_tier'] === 'on'){

            //make sure free tier file is set
            if(
                !isset($_FILES['free_tier_file']) ||
                $_FILES['free_tier_file']['error'] !== 0 ||
                $_FILES['free_tier_file']['type'] !== 'application/pdf'
            ){
                $return['response'] = 'Please make sure you\'ve chosen a free tier file in PDF format or disable free tier';
                return $return;
            }

            //upload free tier file
            $upload_result = $this->upload_file('product_free_tier', $_FILES['free_tier_file']);
            if (!$upload_result['boolean']) {
                $return['response'] = 'Your free tier file couldn\'t be saved';
                return $return;
            }

            //set the uploaded file's name as a post variable to store in db
            $insert_data['free_tier_file'] = $upload_result['content'];
        }

        //make sure header image is set
        if(
            !isset($_FILES['header_image']) ||
            $_FILES['header_image']['error'] !== 0 ||
            $_FILES['header_image']['type'] !== 'image/png'
        ){
            $return['response'] = 'Please make sure you\'ve chosen a header image in PNG format';
            return $return;
        }

        //upload header image
        $upload_result = $this->upload_file('product_header', $_FILES['header_image']);
        if (!$upload_result['boolean']) {
            $return['response'] = 'Your header image couldn\'t be saved';
            return $return;
        }

        //set the uploaded file's name as a post variable to store in db
        $insert_data['header_image'] = $upload_result['content'];

        //make sure thumbnail image is set
        if(
            !isset($_FILES['thumbnail_image']) ||
            $_FILES['thumbnail_image']['error'] !== 0 ||
            $_FILES['thumbnail_image']['type'] !== 'image/png'
        ){
            $return['response'] = 'Please make sure you\'ve chosen a thumbnail image in PNG format';
            return $return;
        }

        //upload header image
        $upload_result = $this->upload_file('product_thumbnail', $_FILES['thumbnail_image']);
        if (!$upload_result['boolean']) {
            $return['response'] = 'Your thumbnail image couldn\'t be saved';
            return $return;
        }

        //set the uploaded file's name as a post variable to store in db
        $insert_data['thumbnail_image'] = $upload_result['content'];

        //define insert data
        $insert_data['insert_time'] = time();
        $insert_data['modified_by'] = $_SESSION['user_id'];

        $insert_data['published'] = 0;
        if(isset($_POST['published']) && $_POST['published'] === 'on'){$insert_data['published'] = 1;}
        $insert_data['enable_free_tier'] = 0;
        if(isset($_POST['enable_free_tier']) && $_POST['enable_free_tier'] === 'on'){$insert_data['enable_free_tier'] = 1;}
        $insert_data['enforce_delta_delivery'] = 0;
        if(isset($_POST['enforce_delta_delivery']) && $_POST['enforce_delta_delivery'] === 'on'){$insert_data['enforce_delta_delivery'] = 1;}
        $insert_data['disable_attachment_delivery'] = 0;
        if(isset($_POST['disable_attachment_delivery']) && $_POST['disable_attachment_delivery'] === 'on'){$insert_data['disable_attachment_delivery'] = 1;}

        $insert_data['name'] = $_POST['name'];
        $insert_data['sku'] = $_POST['sku'];
        $insert_data['subscription_term'] = $_POST['subscription_term'];

        //set container for validation types
        $type_array = array();

        $type_array['free_tier_file'] = 'string';
        $type_array['header_image'] = 'string';
        $type_array['thumbnail_image'] = 'string';
        $type_array['insert_time'] = 'int';
        $type_array['modified_by'] = 'int';
        $type_array['published'] = 'int';
        $type_array['enable_free_tier'] = 'int';
        $type_array['enforce_delta_delivery'] = 'int';
        $type_array['disable_attachment_delivery'] = 'int';
        $type_array['name'] = 'string';
        $type_array['sku'] = 'string';
        $type_array['subscription_term'] = 'int';

        //validate data
        $check = $validate->sanitise_array($insert_data, $type_array, true); //validate and allow blank/zero
        if(!$check){
            $return['response'] = 'Please make sure you\'ve entered valid details into the required fields';
            return $return;
        }

        //run the insert request
        $insert = $mysqli_db->insert('product', $insert_data);
        if(!$insert){
            return $return;
        }

        //return the product id
        $return['product_id'] = $insert;

        //process the paid tier file into the db
        $merge = $this->merge_paid_tier($return['product_id'], $_FILES['paid_tier_file']);
        if(!$merge['boolean']){
            $return['response'] = $merge['response'];
            return $return;
        }

        $return['boolean'] = true;
        $return['response'] = 'Your product has been saved';
        $return['state_fields'] = $merge['state_fields'];
        $return['state_values'] = $merge['state_values'];
        $return['postcode_fields'] = $merge['postcode_fields'];
        return $return;
    }


    //step two of insert (pricing and descriptions)
    function complete_insert($id = 0)
    {
        global $validate, $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to save your product, please try again.',
            'product_id' => 0
        );

        //make sure the referrer is correct
        if (!$validate->check_referer()) {
            return $return;
        }

        //make sure a product id has been passed in
        if((!isset($_POST['product_id']) || empty($_POST['product_id'])) && (!isset($id) || empty($id))){

            $return['response'] = 'Your product couldn\'t be found';
            return $return;
        }

        //save product id for return
        if(!isset($_POST['product_id']) || empty($_POST['product_id'])){
            $return['product_id'] = $id;
        }else{
            $return['product_id'] = $_POST['product_id'];
        }

        //set container for product update data
        $update_data = array();

        //define update data
        $update_data['update_time'] = time();
        $update_data['modified_by'] = $_SESSION['user_id'];
        $update_data['short_description'] = $_POST['short_description'];
        $update_data['description'] = $_POST['description'];
        $update_data['sample_data'] = $_POST['sample_data'];
        $update_data['paid_tier_terms'] = $_POST['paid_tier_terms'];
        $update_data['free_tier_terms'] = $_POST['free_tier_terms'];

        $update_data['national_filter_enabled'] = 0;
        if(isset($_POST['enabled']['national']['filter']) && $_POST['enabled']['national']['filter'] === 'on'){$update_data['national_filter_enabled'] = 1;}
        $update_data['state_filter_enabled'] = 0;
        if(isset($_POST['enabled']['state']['filter']) && $_POST['enabled']['state']['filter'] === 'on'){$update_data['state_filter_enabled'] = 1;}
        $update_data['postcode_filter_enabled'] = 0;
        if(isset($_POST['enabled']['postcode']['filter']) && $_POST['enabled']['postcode']['filter'] === 'on'){$update_data['postcode_filter_enabled'] = 1;}

        $update_data['national_onceoff_enabled'] = 0;
        if(isset($_POST['enabled']['national']['onceoff']) && $_POST['enabled']['national']['onceoff'] === 'on'){$update_data['national_onceoff_enabled'] = 1;}
        $update_data['state_onceoff_enabled'] = 0;
        if(isset($_POST['enabled']['state']['onceoff']) && $_POST['enabled']['state']['onceoff'] === 'on'){$update_data['state_onceoff_enabled'] = 1;}
        $update_data['postcode_onceoff_enabled'] = 0;
        if(isset($_POST['enabled']['postcode']['onceoff']) && $_POST['enabled']['postcode']['onceoff'] === 'on'){$update_data['postcode_onceoff_enabled'] = 1;}

        $update_data['national_quarterly_enabled'] = 0;
        if(isset($_POST['enabled']['national']['quarterly']) && $_POST['enabled']['national']['quarterly'] === 'on'){$update_data['national_quarterly_enabled'] = 1;}
        $update_data['state_quarterly_enabled'] = 0;
        if(isset($_POST['enabled']['state']['quarterly']) && $_POST['enabled']['state']['quarterly'] === 'on'){$update_data['state_quarterly_enabled'] = 1;}
        $update_data['postcode_quarterly_enabled'] = 0;
        if(isset($_POST['enabled']['postcode']['quarterly']) && $_POST['enabled']['postcode']['quarterly'] === 'on'){$update_data['postcode_quarterly_enabled'] = 1;}

        $update_data['national_monthly_enabled'] = 0;
        if(isset($_POST['enabled']['national']['monthly']) && $_POST['enabled']['national']['monthly'] === 'on'){$update_data['national_monthly_enabled'] = 1;}
        $update_data['state_monthly_enabled'] = 0;
        if(isset($_POST['enabled']['state']['monthly']) && $_POST['enabled']['state']['monthly'] === 'on'){$update_data['state_monthly_enabled'] = 1;}
        $update_data['postcode_monthly_enabled'] = 0;
        if(isset($_POST['enabled']['postcode']['monthly']) && $_POST['enabled']['postcode']['monthly'] === 'on'){$update_data['postcode_monthly_enabled'] = 1;}

        $update_data['state_haystack_active'] = array_values(array_filter(explode(',', $_POST['filter']['state']), 'strlen'));
        $update_data['state_haystack_active'] = json_encode($update_data['state_haystack_active']);

        $update_data['postcode_haystack_active'] = array_values(array_filter(explode(',', $_POST['filter']['postcode']), 'strlen'));
        $update_data['postcode_haystack_active'] = json_encode($update_data['postcode_haystack_active']);

        $update_data['national_onceoff_price'] = $_POST['price']['national']['onceoff'];
        $update_data['national_quarterly_price'] = $_POST['price']['national']['quarterly'];
        $update_data['national_monthly_price'] = $_POST['price']['national']['monthly'];

        $update_data['state_onceoff_price'] = json_encode($_POST['price']['state']['onceoff']);
        $update_data['state_quarterly_price'] = json_encode($_POST['price']['state']['quarterly']);
        $update_data['state_monthly_price'] = json_encode($_POST['price']['state']['monthly']);

        $update_data['postcode_onceoff_price'] = $_POST['price']['postcode']['onceoff'];
        $update_data['postcode_quarterly_price'] = $_POST['price']['postcode']['quarterly'];
        $update_data['postcode_monthly_price'] = $_POST['price']['postcode']['monthly'];

        //set container for validation types
        $type_array = array();

        $type_array['update_time'] = 'int';
        $type_array['modified_by'] = 'int';
        $type_array['short_description'] = 'string';
        $type_array['description'] = 'string';
        $type_array['sample_data'] = 'string';
        $type_array['paid_tier_terms'] = 'string';
        $type_array['free_tier_terms'] = 'string';
        $type_array['national_filter_enabled'] = 'int';
        $type_array['state_filter_enabled'] = 'int';
        $type_array['postcode_filter_enabled'] = 'int';
        $type_array['national_onceoff_enabled'] = 'int';
        $type_array['state_onceoff_enabled'] = 'int';
        $type_array['postcode_onceoff_enabled'] = 'int';
        $type_array['national_quarterly_enabled'] = 'int';
        $type_array['state_quarterly_enabled'] = 'int';
        $type_array['postcode_quarterly_enabled'] = 'int';
        $type_array['national_monthly_enabled'] = 'int';
        $type_array['state_monthly_enabled'] = 'int';
        $type_array['postcode_monthly_enabled'] = 'int';
        $type_array['state_haystack_active'] = 'string';
        $type_array['postcode_haystack_active'] = 'string';
        $type_array['national_onceoff_price'] = 'float';
        $type_array['national_quarterly_price'] = 'float';
        $type_array['national_monthly_price'] = 'float';
        $type_array['state_onceoff_price'] = 'string';
        $type_array['state_quarterly_price'] = 'string';
        $type_array['state_monthly_price'] = 'string';
        $type_array['postcode_onceoff_price'] = 'float';
        $type_array['postcode_quarterly_price'] = 'float';
        $type_array['postcode_monthly_price'] = 'float';

        //validate data
        $check = $validate->sanitise_array($update_data, $type_array, true); //validate and allow blank/zero
        if(!$check){
            $return['response'] = 'Please make sure you\'ve entered valid details into the required fields';
            return $return;
        }

        //run the update request
        $mysqli_db->where('product_id', $return['product_id']);
        $update = $mysqli_db->update('product', $update_data);
        if(!$update){
            return $return;
        }

        $return['boolean'] = true;
        $return['response'] = 'Your product has been saved';
        return $return;
    }


    //update product
    function update($id)
    {
        global $validate, $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to save your product, please try again',
        );

        if(empty($id) || !$validate->sanitise_handler($id, 'int', false)) {
            return $return;
        }

        //make sure the referrer is correct
        if (!$validate->check_referer()) {
            return $return;
        }

        //get existing product record
        $existing_product = $this->get_by_id($id);
        if(!$existing_product['boolean']){
            $return['response'] = $existing_product['response'];
            return $return;
        }
        $existing_product = $existing_product['content'];

        //make sure post fields are set
        if (
            !isset($_POST['name']) ||
            !isset($_POST['sku']) ||
            !isset($_POST['subscription_term']) ||
            empty($_POST['name']) ||
            empty($_POST['sku']) ||
            empty($_POST['subscription_term'])
        ){
            $return['response'] = 'Please make sure you\'ve entered a product name, SKU and subscription term';
            return $return;
        }

        //set container for product update data
        $update_data = array();

        //if paid tier file was submitted
        if(
            isset($_FILES['paid_tier_file']) &&
            $_FILES['paid_tier_file']['size'] > 0
        ){

            //if paid tier file is valid
            if($_FILES['paid_tier_file']['error'] !== 0 ||
            (
                $_FILES['paid_tier_file']['type'] !== 'application/octet-stream' &&
                $_FILES['paid_tier_file']['type'] !== 'application/vnd.ms-excel' &&
                $_FILES['paid_tier_file']['type'] !== 'text/plain' &&
                $_FILES['paid_tier_file']['type'] !== 'text/csv' &&
                $_FILES['paid_tier_file']['type'] !== 'text/tsv'
            )){
                $return['response'] = 'Please make sure your paid tier file is in CSV format';
                return $return;
            }

            //make sure a paid data merging option has been chosen
            if(!isset($_POST['append_replace']) || empty($_POST['append_replace']) || ($_POST['append_replace'] !== 'append' && $_POST['append_replace'] !== 'replace')){
                $return['response'] = 'Please make sure you\'ve chosen whether to append to or replace existing paid tier data';
                return $return;
            }

            //process the paid tier file into the db
            $merge = $this->merge_paid_tier($id, $_FILES['paid_tier_file'], $_POST['append_replace']);
            if(!$merge['boolean']){
                $return['response'] = $merge['response'];
                return $return;
            }
        }

        //if free tier is enabled and a free tier file has been submitted
        if(
            isset($_POST['enable_free_tier']) &&
            $_POST['enable_free_tier'] === 'on' &&
            isset($_FILES['free_tier_file']) &&
            $_FILES['free_tier_file']['size'] > 0
        ){

            //check if free tier file is valid
            if(
                $_FILES['free_tier_file']['error'] !== 0 ||
                $_FILES['free_tier_file']['type'] !== 'application/pdf'
            ){
                $return['response'] = 'Please make sure you\'ve chosen a free tier file in PDF format';
                return $return;
            }

            //upload free tier file
            $upload_result = $this->upload_file('product_free_tier', $_FILES['free_tier_file']);
            if (!$upload_result['boolean']) {
                $return['response'] = 'Your free tier file couldn\'t be saved';
                return $return;
            }

            //delete old free tier file
            if(!empty($existing_product['free_tier_file'])){

                //delete file from S3
                include_once(LOCAL.'secure/class/S3.php');
                $s3 = new S3(IAM_KEY_ID, IAM_KEY_SECRET);
                $s3->deleteObject(S3_BUCKET, S3_FOLDER.'/product_free_tier/'.$existing_product['free_tier_file']);
            }

            //set the uploaded file's name as a post variable to store in db
            $update_data['free_tier_file'] = $upload_result['content'];
        }

        //if free tier has been disabled
        if(isset($_POST['enable_free_tier']) && $_POST['enable_free_tier'] === 'off'){

            //delete old free tier file
            if(!empty($existing_product['free_tier_file'])){

                //delete file from S3
                include_once(LOCAL.'secure/class/S3.php');
                $s3 = new S3(IAM_KEY_ID, IAM_KEY_SECRET);
                $s3->deleteObject(S3_BUCKET, S3_FOLDER.'/product_free_tier/'.$existing_product['free_tier_file']);
            }

            //remove free tier file reference
            $update_data['free_tier_file'] = '';
        }

        //if a header image has been submitted
        if(isset($_FILES['header_image']) && $_FILES['header_image']['size'] > 0){

            //check if header image is valid
            if(
                $_FILES['header_image']['error'] !== 0 ||
                $_FILES['header_image']['type'] !== 'image/png'
            ){
                $return['response'] = 'Please make sure you\'ve chosen a header image in PNG format';
                return $return;
            }

            //upload header image
            $upload_result = $this->upload_file('product_header', $_FILES['header_image']);
            if (!$upload_result['boolean']) {
                $return['response'] = 'Your header image couldn\'t be saved';
                return $return;
            }

            //delete old header image
            if(!empty($existing_product['header_image'])){

                //delete file from S3
                include_once(LOCAL.'secure/class/S3.php');
                $s3 = new S3(IAM_KEY_ID, IAM_KEY_SECRET);
                $s3->deleteObject(S3_BUCKET, S3_FOLDER.'/product_header/'.$existing_product['header_image']);
            }

            //set the uploaded file's name as a post variable to store in db
            $update_data['header_image'] = $upload_result['content'];
        }

        //if a thumbnail image has been submitted
        if(isset($_FILES['thumbnail_image']) && $_FILES['thumbnail_image']['size'] > 0){

            //check if thumbnail image is valid
            if(
                $_FILES['thumbnail_image']['error'] !== 0 ||
                $_FILES['thumbnail_image']['type'] !== 'image/png'
            ){
                $return['response'] = 'Please make sure you\'ve chosen a thumbnail image in PNG format';
                return $return;
            }

            //upload thumbnail image
            $upload_result = $this->upload_file('product_thumbnail', $_FILES['thumbnail_image']);
            if (!$upload_result['boolean']) {
                $return['response'] = 'Your thumbnail image couldn\'t be saved';
                return $return;
            }

            //delete old thumbnail image
            if(!empty($existing_product['thumbnail_image'])){

                //delete file from S3
                include_once(LOCAL.'secure/class/S3.php');
                $s3 = new S3(IAM_KEY_ID, IAM_KEY_SECRET);
                $s3->deleteObject(S3_BUCKET, S3_FOLDER.'/product_thumbnail/'.$existing_product['thumbnail_image']);
            }

            //set the uploaded file's name as a post variable to store in db
            $update_data['thumbnail_image'] = $upload_result['content'];
        }

        //set update data
        $update_data['update_time'] = time() - 20; //update time will be reset by complete this second causing an update error if the times are the same
        $update_data['modified_by'] = $_SESSION['user_id'];

        $update_data['published'] = 0;
        if(isset($_POST['published']) && $_POST['published'] === 'on'){$update_data['published'] = 1;}
        $update_data['enable_free_tier'] = 0;
        if(isset($_POST['enable_free_tier']) && $_POST['enable_free_tier'] === 'on'){$update_data['enable_free_tier'] = 1;}
        $update_data['enforce_delta_delivery'] = 0;
        if(isset($_POST['enforce_delta_delivery']) && $_POST['enforce_delta_delivery'] === 'on'){$update_data['enforce_delta_delivery'] = 1;}
        $update_data['disable_attachment_delivery'] = 0;
        if(isset($_POST['disable_attachment_delivery']) && $_POST['disable_attachment_delivery'] === 'on'){$update_data['disable_attachment_delivery'] = 1;}

        $update_data['name'] = $_POST['name'];
        $update_data['sku'] = $_POST['sku'];
        $update_data['subscription_term'] = $_POST['subscription_term'];
        $update_data['short_description'] = $_POST['short_description'];
        $update_data['description'] = $_POST['description'];
        $update_data['sample_data'] = $_POST['sample_data'];
        $update_data['paid_tier_terms'] = $_POST['paid_tier_terms'];
        $update_data['free_tier_terms'] = $_POST['free_tier_terms'];

        $update_data['national_filter_enabled'] = 0;
        if(isset($_POST['enabled']['national']['filter']) && $_POST['enabled']['national']['filter'] === 'on'){$update_data['national_filter_enabled'] = 1;}
        $update_data['state_filter_enabled'] = 0;
        if(isset($_POST['enabled']['state']['filter']) && $_POST['enabled']['state']['filter'] === 'on'){$update_data['state_filter_enabled'] = 1;}
        $update_data['postcode_filter_enabled'] = 0;
        if(isset($_POST['enabled']['postcode']['filter']) && $_POST['enabled']['postcode']['filter'] === 'on'){$update_data['postcode_filter_enabled'] = 1;}

        $update_data['national_onceoff_enabled'] = 0;
        if(isset($_POST['enabled']['national']['onceoff']) && $_POST['enabled']['national']['onceoff'] === 'on'){$update_data['national_onceoff_enabled'] = 1;}
        $update_data['state_onceoff_enabled'] = 0;
        if(isset($_POST['enabled']['state']['onceoff']) && $_POST['enabled']['state']['onceoff'] === 'on'){$update_data['state_onceoff_enabled'] = 1;}
        $update_data['postcode_onceoff_enabled'] = 0;
        if(isset($_POST['enabled']['postcode']['onceoff']) && $_POST['enabled']['postcode']['onceoff'] === 'on'){$update_data['postcode_onceoff_enabled'] = 1;}

        $update_data['national_quarterly_enabled'] = 0;
        if(isset($_POST['enabled']['national']['quarterly']) && $_POST['enabled']['national']['quarterly'] === 'on'){$update_data['national_quarterly_enabled'] = 1;}
        $update_data['state_quarterly_enabled'] = 0;
        if(isset($_POST['enabled']['state']['quarterly']) && $_POST['enabled']['state']['quarterly'] === 'on'){$update_data['state_quarterly_enabled'] = 1;}
        $update_data['postcode_quarterly_enabled'] = 0;
        if(isset($_POST['enabled']['postcode']['quarterly']) && $_POST['enabled']['postcode']['quarterly'] === 'on'){$update_data['postcode_quarterly_enabled'] = 1;}

        $update_data['national_monthly_enabled'] = 0;
        if(isset($_POST['enabled']['national']['monthly']) && $_POST['enabled']['national']['monthly'] === 'on'){$update_data['national_monthly_enabled'] = 1;}
        $update_data['state_monthly_enabled'] = 0;
        if(isset($_POST['enabled']['state']['monthly']) && $_POST['enabled']['state']['monthly'] === 'on'){$update_data['state_monthly_enabled'] = 1;}
        $update_data['postcode_monthly_enabled'] = 0;
        if(isset($_POST['enabled']['postcode']['monthly']) && $_POST['enabled']['postcode']['monthly'] === 'on'){$update_data['postcode_monthly_enabled'] = 1;}

        $update_data['state_haystack_active'] = array_values(array_filter(explode(',', $_POST['filter']['state']), 'strlen'));
        $update_data['state_haystack_active'] = json_encode($update_data['state_haystack_active']);

        $update_data['postcode_haystack_active'] = array_values(array_filter(explode(',', $_POST['filter']['postcode']), 'strlen'));
        $update_data['postcode_haystack_active'] = json_encode($update_data['postcode_haystack_active']);

        $update_data['national_onceoff_price'] = $_POST['price']['national']['onceoff'];
        $update_data['national_quarterly_price'] = $_POST['price']['national']['quarterly'];
        $update_data['national_monthly_price'] = $_POST['price']['national']['monthly'];

        $update_data['state_onceoff_price'] = json_encode($_POST['price']['state']['onceoff']);
        $update_data['state_quarterly_price'] = json_encode($_POST['price']['state']['quarterly']);
        $update_data['state_monthly_price'] = json_encode($_POST['price']['state']['monthly']);

        $update_data['postcode_onceoff_price'] = $_POST['price']['postcode']['onceoff'];
        $update_data['postcode_quarterly_price'] = $_POST['price']['postcode']['quarterly'];
        $update_data['postcode_monthly_price'] = $_POST['price']['postcode']['monthly'];


        //set container for validation types
        $type_array = array();

        $type_array['free_tier_file'] = 'string';
        $type_array['header_image'] = 'string';
        $type_array['thumbnail_image'] = 'string';
        $type_array['update_time'] = 'int';
        $type_array['modified_by'] = 'int';
        $type_array['published'] = 'int';
        $type_array['enable_free_tier'] = 'int';
        $type_array['enforce_delta_delivery'] = 'int';
        $type_array['disable_attachment_delivery'] = 'int';
        $type_array['name'] = 'string';
        $type_array['sku'] = 'string';
        $type_array['subscription_term'] = 'int';
        $type_array['short_description'] = 'string';
        $type_array['description'] = 'string';
        $type_array['sample_data'] = 'string';
        $type_array['paid_tier_terms'] = 'string';
        $type_array['free_tier_terms'] = 'string';
        $type_array['national_filter_enabled'] = 'int';
        $type_array['state_filter_enabled'] = 'int';
        $type_array['postcode_filter_enabled'] = 'int';
        $type_array['national_onceoff_enabled'] = 'int';
        $type_array['state_onceoff_enabled'] = 'int';
        $type_array['postcode_onceoff_enabled'] = 'int';
        $type_array['national_quarterly_enabled'] = 'int';
        $type_array['state_quarterly_enabled'] = 'int';
        $type_array['postcode_quarterly_enabled'] = 'int';
        $type_array['national_monthly_enabled'] = 'int';
        $type_array['state_monthly_enabled'] = 'int';
        $type_array['postcode_monthly_enabled'] = 'int';
        $type_array['state_haystack_active'] = 'string';
        $type_array['postcode_haystack_active'] = 'string';
        $type_array['national_onceoff_price'] = 'float';
        $type_array['national_quarterly_price'] = 'float';
        $type_array['national_monthly_price'] = 'float';
        $type_array['state_onceoff_price'] = 'string';
        $type_array['state_quarterly_price'] = 'string';
        $type_array['state_monthly_price'] = 'string';
        $type_array['postcode_onceoff_price'] = 'float';
        $type_array['postcode_quarterly_price'] = 'float';
        $type_array['postcode_monthly_price'] = 'float';

        //if we performed a paid merge
        if(isset($merge) && isset($merge['boolean']) && $merge['boolean']){

            //if the product data was replaced we need to update the haystack fields
            if($_POST['append_replace'] === 'replace'){

                //update state and postcode data haystack fields to match the new product data
                $update_data['state_haystack_active'] = json_encode($merge['state_fields']);
                $update_data['postcode_haystack_active'] = json_encode($merge['postcode_fields']);
            }

            //decode state pricing
            $update_data['state_onceoff_price'] = (array)json_decode($update_data['state_onceoff_price']);
            $update_data['state_quarterly_price'] = (array)json_decode($update_data['state_quarterly_price']);
            $update_data['state_monthly_price'] = (array)json_decode($update_data['state_monthly_price']);

            //check for any new state values from the newly merged data
            foreach($merge['state_values'] as $k => $v){

                if(!array_key_exists($v, $update_data['state_onceoff_price'])){
                    $update_data['state_onceoff_price'][$v] = '';
                }
                if(!array_key_exists($v, $update_data['state_quarterly_price'])){
                    $update_data['state_quarterly_price'][$v] = '';
                }
                if(!array_key_exists($v, $update_data['state_monthly_price'])){
                    $update_data['state_monthly_price'][$v] = '';
                }
            }

            //re-encode state pricing
            $update_data['state_onceoff_price'] = json_encode($update_data['state_onceoff_price']);
            $update_data['state_quarterly_price'] = json_encode($update_data['state_quarterly_price']);
            $update_data['state_monthly_price'] = json_encode($update_data['state_monthly_price']);
        }

        //validate data
        $check = $validate->sanitise_array($update_data, $type_array, true); //validate and allow blank/zero
        if(!$check){
            $return['response'] = 'Please make sure you\'ve entered valid details into the required fields';
            return $return;
        }

        //run the update request
        $mysqli_db->where('product_id', $id);
        $update = $mysqli_db->update('product', $update_data);
        if(!$update){
            return $return;
        }

        $return['boolean'] = true;
        $return['response'] = 'Your product has been saved';
        return $return;
    }


    //build and return product cross sell data
    public function get_cross_sell($id)
    {
        global $mysqli_db, $validate;

        $return = array(
            'boolean' => false,
            'response' => 'Additional products couldn\'t be found',
            'content' => '',
        );

        //make sure product id is safe
        if(!isset($id) || empty($id) || !$validate->sanitise_handler($id, 'int', false)){
            return $return;
        }

        //select paid product with required fields and randomise the results
        $select = $mysqli_db->query('SELECT `product_id`, `name`, `thumbnail_image` FROM `product` WHERE `product_id` != '.$id.' AND `published` = 1 AND `name` != "" AND `sku` != "" AND `subscription_term` != 0 LIMIT 6');
        shuffle($select);

        if(isset($select) && !empty($select)){
            $return['boolean'] = true;
            $return['content'] = $select;
        }

        return $return;
    }

}
