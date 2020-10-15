<?php
///////////////////////////////////////////////////////////////////////////////////
// Report Class
// Site: postcode.auspost.com.au
// Purpose: Manage subscription products
// Version 0.0.1
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
///////////////////////////////////////////////////////////////////////////////////

class report
{

    private $_csv_chunk_interval = 500; //how many data rows to pull when generating CSVs

    //clean up really old data - product download history log's getting out of hand (reached 41,750)
    public function __construct()
    {

        //bring dependant libraries into scope
        global $mysqli_db;

        //delete any records that are more than a week old
        $ago = strtotime('- 2 year');
        $less_ago = strtotime('- 6 months');

        //perform the wipes, no need to listen for response
        $mysqli_db->raw_query('DELETE FROM `product_download_history` WHERE `tier` = "free" AND `insert_time` < '.$less_ago, false);
        $mysqli_db->raw_query('DELETE FROM `product_download_history` WHERE `tier` = "paid" AND `insert_time` < '.$ago, false);
        $mysqli_db->raw_query('DELETE FROM `email_bounce_log` WHERE `insert_time` < '.$ago, false);
        $mysqli_db->raw_query('DELETE FROM `email_complaint_log` WHERE `insert_time` < '.$ago, false);

        return true;
    }


    public function get_metrics()
    {
        global $mysqli_db;

        $return = array();

        //set time boundaries
        $thirty_days_ago = strtotime('-30 days');
        $year_days_ago = strtotime('-365 days');
        $start_of_current_month = strtotime('first day of this month');
        $start_of_last_month = strtotime('first day of last month');
        $start_of_current_month_last_year = strtotime('first day of this month last year');
        $end_of_current_month_last_year = strtotime('last day of this month last year');

        //set the financial year to July last year, if july hasn't happened yet
        $financial_year_ago = strtotime('first day of july');
        if ($financial_year_ago > time()) {
            $financial_year_ago = strtotime("-1 year", $financial_year_ago);
        }
        $financial_two_years_ago = $financial_year_ago - strtotime('365 days');

        //current subscribers
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `expiry_time` > ' . time() . ' AND `manually_terminated` = 0 AND `paid_time` > 0';
        $return['current_subscribers'] = $mysqli_db->raw_query($query_string, false)[0]['count'];

        //subscribers this month
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `insert_time` > ' . $start_of_current_month . ' AND `paid_time` > 0  AND renew_parent_id = "" AND renew_child_id = ""';
        $return['subscribers_this_month'] = $mysqli_db->raw_query($query_string, false)[0]['count'];

        //subscribers this year
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `insert_time` > ' . $financial_year_ago . ' AND `paid_time` > 0  AND renew_parent_id = "" AND renew_child_id = ""';
        $return['subscribers_this_year'] = $mysqli_db->raw_query($query_string, false)[0]['count'];

        //renewed subscribers this month
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `insert_time` > ' . $start_of_current_month . ' AND `paid_time` > 0  AND renew_parent_id != ""';
        $return['renewed_subscribers_this_month'] = $mysqli_db->raw_query($query_string, false)[0]['count'];

/*
        NOTE: THIS RUNS OUT OF MEMORY, it was supposed to be more inclusive to find manually re-subscribed subscribers
        $query_string = '
        SELECT
            *
        FROM
            `subscriber` AS S1
        JOIN
            `subscriber` AS S2 ON S1.`delivery_email` = S2.`delivery_email`
                AND S1.`product` = S2.`product`
                AND S1.`product_data_filter` = S2.`product_data_filter`
                AND S1.`product_data_needle` = S2.`product_data_needle`
                AND S1.`product_delivery_interval` = S2.`product_delivery_interval`
                AND S2.`renew_child_id` = ""
        		AND S2.`insert_time` > ' . $start_of_current_month_last_year . '
                AND S2.`paid_time` > 0
        WHERE
            S1.`insert_time` > ' . $start_of_current_month . '
                AND S1.`paid_time` > 0
                AND S1.`renew_parent_id` != ""
        ';
        $return['renewed_subscribers_this_month'] = count($mysqli_db->raw_query($query_string, false));
*/

        //renewed subscribers this year - NOTE: now also includes manually re-subscribed by mnatching delivery email
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `insert_time` > ' . $financial_year_ago . ' AND `paid_time` > 0  AND renew_parent_id != ""';
        $return['renewed_subscribers_this_year'] = $mysqli_db->raw_query($query_string, false)[0]['count'];

/*
        NOTE: THIS RUNS OUT OF MEMORY, it was supposed to be more inclusive to find manually re-subscribed subscribers
        $query_string = '
        SELECT
            *
        FROM
            `subscriber` AS S1
        JOIN
            `subscriber` AS S2 ON S1.`delivery_email` = S2.`delivery_email`
                AND S1.`product` = S2.`product`
                AND S1.`product_data_filter` = S2.`product_data_filter`
                AND S1.`product_data_needle` = S2.`product_data_needle`
                AND S1.`product_delivery_interval` = S2.`product_delivery_interval`
                AND S2.`renew_child_id` = ""
        		AND S2.`insert_time` > ' . $financial_two_years_ago . '
                AND S2.`paid_time` > 0
        WHERE
            S1.`insert_time` > ' . $financial_year_ago . '
                AND S1.`paid_time` > 0
                AND S1.`renew_parent_id` != ""
        ';
        $return['renewed_subscribers_this_year'] = 0;//count($mysqli_db->raw_query($query_string, false));
*/

        //expired this month
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `paid_time` > 0 AND (`expiry_time` > ' . $start_of_current_month . ' AND `expiry_time` < ' . time() . ' OR `manually_terminated` > ' . $start_of_current_month . ' AND `manually_terminated` < ' . time() . ')   AND  renew_child_id = "" ';
        $return['expired_this_month'] = $mysqli_db->raw_query($query_string, false)[0]['count'];

        //expired this year
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `paid_time` > 0 AND (`expiry_time` > ' . $financial_year_ago . ' AND `expiry_time` < ' . time() . ' OR `manually_terminated` > ' . $financial_year_ago . ' AND `manually_terminated` < ' . time() . ')  AND  renew_child_id = "" ';
        $return['expired_this_year'] = $mysqli_db->raw_query($query_string, false)[0]['count'];

        //increase in subscribers between this month and last month
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `insert_time` > ' . $start_of_last_month . ' AND `insert_time` < ' . $start_of_current_month . ' AND `paid_time` > 0 AND renew_parent_id = "" AND  renew_child_id = ""  ';
        $return['subscribers_last_month'] = $mysqli_db->raw_query($query_string, false)[0]['count'];

        //calculate percentage increase (or descrease)
        if ($return['subscribers_this_month'] > 0 && $return['subscribers_last_month'] > 0) {
            $percent = $return['subscribers_this_month'] / $return['subscribers_last_month'];
            $percent = ($percent * 100) - 100;
            $percent = number_format($percent, 0);
            // Display negative % with a down arrow (and remove negative) and positive % with an up arrow
            if ($percent > 0) {
                $percent = '&#x25B4;' . $percent . '%';
            } else {
                $percent = $percent - ($percent * 2);
                $percent = '&#x25BE;' . $percent . '%';
            }
            $return['subscribers_increase_percent'] = $percent;
        } else {
            $return['subscribers_increase_percent'] = 'N/A';
        }

        //current national subscribers
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `paid_time` > 0 AND `expiry_time` > ' . time() . ' AND `manually_terminated` = 0 AND `product_data_filter` = "national"';
        $return['current_national'] = $mysqli_db->raw_query($query_string, false)[0]['count'];

        //current state subscribers
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `paid_time` > 0 AND `expiry_time` > ' . time() . ' AND `manually_terminated` = 0 AND `product_data_filter` = "state"';
        $return['current_state'] = $mysqli_db->raw_query($query_string, false)[0]['count'];

        //current postcode subscribers
        $query_string = 'SELECT count(`subscriber_id`) count FROM `subscriber` WHERE `paid_time` > 0 AND `expiry_time` > ' . time() . ' AND `manually_terminated` = 0 AND `product_data_filter` = "postcode"';
        $return['current_postcode'] = $mysqli_db->raw_query($query_string, false)[0]['count'];

        //revenue this month
        $query_string = 'SELECT `paid_total` FROM `subscriber` WHERE `paid_time` > ' . $start_of_current_month . ' AND `insert_time` > 0 AND `manually_terminated` = 0';
        $revenue_this_month = $mysqli_db->raw_query($query_string, false);
        $revenue_this_month = round(array_sum(array_map(function ($item) {
            return $item['paid_total'];
        }, $revenue_this_month)));
        $return['revenue_this_month'] = '$' . number_format($revenue_this_month);

        //increase in revenue this month vs this month last year
        $query_string = 'SELECT `paid_total` FROM `subscriber` WHERE `paid_time` > ' . $start_of_current_month_last_year . ' AND `paid_time` < '.$end_of_current_month_last_year.' AND `insert_time` > 0 AND `manually_terminated` = 0';
        $revenue_this_month_last_year = $mysqli_db->raw_query($query_string, false);
        $revenue_this_month_last_year = round(array_sum(array_map(function ($item) {
            return $item['paid_total'];
        }, $revenue_this_month_last_year)));
        $return['revenue_this_month_last_year'] = '$' . number_format($revenue_this_month_last_year);

        //calculate percentage increase (or descrease)
        if ($revenue_this_month > 0 && $revenue_this_month_last_year > 0) {
            $percent = $revenue_this_month / $revenue_this_month_last_year;
            $percent = ($percent * 100) - 100;
            $percent = number_format($percent, 0);

            //display negative % with a down arrow (and remove negative) and positive % with an up arrow
            if ($percent > 0) {
                $percent = '&#x25B4;' . $percent . '%';
            } else {
                $percent = $percent - ($percent * 2);
                $percent = '&#x25BE;' . $percent . '%';
            }
            $return['revenue_this_month_last_year_increase_percent'] = $percent;
        } else {
            $return['revenue_this_month_last_year_increase_percent'] = 'N/A';
        }

        //revenue this financial year
        $query_string = 'SELECT `paid_total` FROM `subscriber` WHERE `paid_time` > ' . $financial_year_ago . ' AND `paid_time` > 0 AND `manually_terminated` = 0';
        $revenue_this_year = $mysqli_db->raw_query($query_string, false);
        $return['revenue_this_year'] = '$' . number_format(round(array_sum(array_map(function ($item) {
            return $item['paid_total'];
        }, $revenue_this_year))));

        //revenue last 30 days
        $query_string = 'SELECT `paid_total` FROM `subscriber` WHERE `paid_time` > ' . $thirty_days_ago . ' AND `insert_time` > 0 AND `manually_terminated` = 0';
        $revenue_last_thirty_days = $mysqli_db->raw_query($query_string, false);
        $return['revenue_last_30_days'] = '$' . number_format(round(array_sum(array_map(function ($item) {
            return $item['paid_total'];
        }, $revenue_last_thirty_days))));

        //revenue last 365 days
        $query_string = 'SELECT `paid_total` FROM `subscriber` WHERE `paid_time` > ' . $year_days_ago . ' AND `paid_time` > 0 AND `manually_terminated` = 0';
        $revenue_last_year_days = $mysqli_db->raw_query($query_string, false);
        $return['revenue_last_365_days'] = '$' . number_format(round(array_sum(array_map(function ($item) {
            return $item['paid_total'];
        }, $revenue_last_year_days))));

        //email logs counters this month
        $query_string = 'SELECT (SELECT count(*) FROM email_bounce_log WHERE insert_time > ' . $start_of_current_month . '  ) bounce_counter, (SELECT count(*) FROM email_complaint_log WHERE insert_time > ' . $start_of_current_month . ' ) complaint_counter ';
        $email_log = $mysqli_db->raw_query($query_string, false);
        $return['email_bounces_month'] = $email_log[0]['bounce_counter'];
        $return['email_complaints_month'] = $email_log[0]['complaint_counter'];

        return $return;
    }

    //clean data for csv
    private function escape_for_csv($value)
    {
        //first off escape all " and make them ""

        $value = str_replace('"', '""', $value);

        //check if we have any commas or new lines
        if (strpos($value, ",") or strpos($value, "\n")) {
            //if we have new lines or commas escape them
            return '"' . $value . '"';
        } else {
            //if no new lines or commas just return the value
            return $value;
        }
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

    //header our subscribers csv
    public function get_subscribers_csv($view = 'current')
    {
        global $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => ''
        );

        //set headers
        $this->download_headers($view.'_subscribers_' . date('dmY') . '.csv');

        //open file stream
        $fh = @fopen('php://output', 'w');

        //set the csv column titles
        $header_row = array(
            'Invoice #',
            'Creation Date',
            'Last Updated',
            'Product',
            'Paid Date',
            'Paid Total',
            'Payment Transaction Reference',
            'Expiry Date',
            'Manually Terminated',
            'Reseller',
            'Business Name',
            'Contact Name',
            'Department',
            'Delivery Email Address',
            'Support Email Address',
            'Opted In For Marketing',
            'Last File Delivery Date',
            'Product Data-set Category',
            'Product Data-set Search',
            'Product Delivery Interval',
            'Last Download Date',
            'Download Count',
            'Renewed'
        );
        fputcsv($fh, $header_row);

        //find our starting id and set first row id for recursion
        $query_string = 'SELECT `subscriber_id` FROM `'.$view.'_subscriber` ORDER BY `subscriber_id` ASC LIMIT 1';
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
            $query_string = 'SELECT
                            `'.$view.'_subscriber`.`subscriber_id`,
                            FROM_UNIXTIME(`'.$view.'_subscriber`.`insert_time`) AS `insert_time`,
                            FROM_UNIXTIME(`'.$view.'_subscriber`.`update_time`) AS `update_time`,
                            `product`.`name` AS `product_name`,
                            FROM_UNIXTIME(`'.$view.'_subscriber`.`paid_time`) AS `paid_time`,
                            CONCAT("$", FORMAT(`'.$view.'_subscriber`.`paid_total`, 2)),
                            `'.$view.'_subscriber`.`paid_reference`,
                            FROM_UNIXTIME(`'.$view.'_subscriber`.`expiry_time`) AS `expiry_time`,
                            `'.$view.'_subscriber`.`manually_terminated`,
                            `'.$view.'_subscriber`.`reseller`,
                            `'.$view.'_subscriber`.`business_name`,
                            `'.$view.'_subscriber`.`contact_name`,
                            `'.$view.'_subscriber`.`business_department`,
                            `'.$view.'_subscriber`.`delivery_email`,
                            `'.$view.'_subscriber`.`support_email`,
                            `'.$view.'_subscriber`.`marketing_opt_in`,
                            FROM_UNIXTIME(`'.$view.'_subscriber`.`last_delivery_time`) AS `last_delivery_time`,
                            `'.$view.'_subscriber`.`product_data_filter`,
                            `'.$view.'_subscriber`.`product_data_needle`,
                            `'.$view.'_subscriber`.`product_delivery_interval`,
                            FROM_UNIXTIME(`'.$view.'_subscriber`.`last_download_time`) AS `last_download_time`,
                            `'.$view.'_subscriber`.`download_count`,
                            `'.$view.'_subscriber`.`renew_parent_id`
                        FROM `'.$view.'_subscriber`
                        LEFT JOIN `product` ON `'.$view.'_subscriber`.`product` = `product`.`product_id`
                        WHERE `'.$view.'_subscriber`.`subscriber_id` >= '.$current_id.'
                        ORDER BY `'.$view.'_subscriber`.`subscriber_id` ASC
                        LIMIT '.$this->_csv_chunk_interval;
            $chunk = $mysqli_db->raw_query($query_string, false);

            //make sure there's a result
            if(empty($chunk)){
                break;
            }

            foreach($chunk as $row){

                //convert int booleans
                if($row['manually_terminated'] != 0){$row['manually_terminated'] = 'TRUE';}else{$row['manually_terminated'] = 'FALSE';}
                if($row['reseller'] != 0){$row['reseller'] = 'TRUE';}else{$row['reseller'] = 'FALSE';}
                if($row['marketing_opt_in'] != 0){$row['marketing_opt_in'] = 'TRUE';}else{$row['marketing_opt_in'] = 'FALSE';}
                if($row['renew_parent_id'] != 0){$row['renew_parent_id'] = 'TRUE';}else{$row['renew_parent_id'] = 'FALSE';}

                //make readable
                $row['product_data_filter'] = ucwords($row['product_data_filter']);
                if(!empty($row['product_data_needle'])){$row['product_data_needle'] = implode(' | ', (array)json_decode($row['product_data_needle']));}else{$row['product_data_needle'] = 'None';}
                $row['product_delivery_interval'] = ucwords($row['product_delivery_interval']);

                //remove zero/1970 dates
                if($row['insert_time'] === "1970-01-01 00:00:00"){$row['insert_time'] = 'Unknown';}
                if($row['update_time'] === "1970-01-01 00:00:00"){$row['update_time'] = 'Unknown';}
                if($row['paid_time'] === "1970-01-01 00:00:00"){$row['paid_time'] = 'Unknown';}
                if($row['expiry_time'] === "1970-01-01 00:00:00"){$row['expiry_time'] = 'Unknown';}
                if($row['last_delivery_time'] === "1970-01-01 00:00:00"){$row['last_delivery_time'] = 'Never';}
                if($row['last_download_time'] === "1970-01-01 00:00:00"){$row['last_download_time'] = 'Never';}

                //write to csv output
                fputcsv($fh, $row);

                //update current id
                $current_id = $row['subscriber_id'] + 1;

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

    //header out free products download data csv
    public function get_free_download_data_csv()
    {
        global $mysqli_db, $shortcut;

        $return = array(
            'boolean' => false,
            'response' => ''
        );

        //set headers
        $this->download_headers('free_download_data_' . date('dmY') . '.csv');

        //open file stream
        $fh = @fopen('php://output', 'w');

        //set the csv column titles
        $header_row = array(
			'Download Date',
			'Product',
            'IP Address',
        );
        fputcsv($fh, $header_row);

        //find our starting id and set first row id for recursion
        $query_string = 'SELECT `product_download_history_id` FROM `product_download_history` WHERE `tier` = "free" ORDER BY `product_download_history_id` ASC LIMIT 1';
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
            $query_string = '
                SELECT
                    `product_download_history_id`,
                    FROM_UNIXTIME(`product_download_history`.`insert_time`) AS `insert_time`,
                    `product_download_history`.`product`,
                    `product_download_history`.`end_user_ip`,
                    `product`.`name` AS `product_name`
                FROM `product_download_history`
                JOIN `product` ON `product_download_history`.`product` = `product`.`product_id`
                WHERE `tier` = "free"
                ORDER BY `product_download_history`.`product_download_history_id` ASC
                LIMIT '.$this->_csv_chunk_interval;
            $chunk = $mysqli_db->raw_query($query_string, false);

            //make sure there's a result
            if(empty($chunk)){
                break;
            }

            foreach($chunk as $row){

                //remove zero/1970 dates
                if($row['insert_time'] === "1970-01-01 00:00:00"){$row['insert_time'] = 'Unknown';}

                //write to csv output
                fputcsv($fh, $row);

                //update current id
                $current_id = $row['product_download_history_id'] + 1;

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


    //header out paid subscribers download data csv
    public function get_paid_download_data_csv()
    {
        global $mysqli_db, $shortcut;

        $return = array(
            'boolean' => false,
            'response' => ''
        );

        //set headers
        $this->download_headers('paid_download_data_' . date('dmY') . '.csv');

        //open file stream
        $fh = @fopen('php://output', 'w');

        //set the csv column titles
        $header_row = array(
			'Download Date',
			'Product',
            'IP Address',
			'Invoice #',
			'Delivery Email',
			'Business Name',
			'Contact Name',
			'Support Email',
            'Product Data Category',
            'Product Data Search',
            'Product Delivery Interval'
        );
        fputcsv($fh, $header_row);

        //find our starting id and set first row id for recursion
        $query_string = 'SELECT `product_download_history_id` FROM `product_download_history` WHERE `tier` = "paid" ORDER BY `product_download_history_id` ASC LIMIT 1';
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
            $query_string = 'SELECT
							FROM_UNIXTIME(`product_download_history`.`insert_time`) AS `insert_time`,
							`product`.`name` AS `product_name`,
                            `product_download_history`.`end_user_ip`,
							`subscriber`.`subscriber_id`,
							`subscriber`.`delivery_email`,
							`subscriber`.`business_name`,
							`subscriber`.`contact_name`,
							`subscriber`.`support_email`,
							`subscriber`.`product_data_filter`,
							`subscriber`.`product_data_needle`,
							`subscriber`.`product_delivery_interval`
							FROM `product_download_history`
							 JOIN `product` ON `product_download_history`.`product` = `product`.`product_id`
							 JOIN `subscriber` ON `product_download_history`.`subscriber` = `subscriber`.`subscriber_id`
							WHERE `product_download_history`.`product_download_history_id` >= '.$current_id.'
                            AND `product_download_history`.`tier` = "paid"
							ORDER BY `product_download_history`.`product_download_history_id` ASC
							LIMIT '.$this->_csv_chunk_interval;
            $chunk = $mysqli_db->raw_query($query_string, false);

            //make sure there's a result
            if(empty($chunk)){
                break;
            }

            foreach($chunk as $row){

                //make readable
                $row['product_data_filter'] = ucwords($row['product_data_filter']);
                if(!empty($row['product_data_needle'])){$row['product_data_needle'] = implode(' | ', (array)json_decode($row['product_data_needle']));}else{$row['product_data_needle'] = 'None';}
                $row['product_delivery_interval'] = ucwords($row['product_delivery_interval']);

                //remove zero/1970 dates
                if($row['insert_time'] === "1970-01-01 00:00:00"){$row['insert_time'] = 'Unknown';}

                //write to csv output
                fputcsv($fh, $row);

                //update current id
                $current_id = $row['subscriber_id'] + 1;

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


    public function get_email_log($type = 'bounce')
    {
        global $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => ''
        );
        $table = 'email_bounce_log';
        if ($type === 'complaint') {
            $table = 'email_complaint_log';
        }

        //filter function to remove our own email addresses
        function notUs($string) {
            return strpos($string, 'postcode') === false;
        }

        $query_string = 'SELECT * FROM '. $table;
        $select = $mysqli_db->raw_query($query_string, false);

        if (!empty($select)) {
            //cav data container
            $csv_data = '';

            //set the csv column titles
            $title_row = array(
                'Problem date',
                'Email Address'
            );
            $csv_data .= join(',', $title_row) . "\n";

            foreach ($select as $s) {
                $insert_time = 'Never';
                if ($s['insert_time'] != 0) {
                    $insert_time = date('d/m/Y g:ia', $s['insert_time']);
                }

                //log data doesn't come in clean anymore, let's hunt for delivery addresses
                $scraped_emails = array();
                $find_email_pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
                preg_match_all($find_email_pattern, $s['header_content'], $scraped_emails);
                preg_match_all($find_email_pattern, $s['body_content'], $scraped_emails);
                if(empty($scraped_emails[0])){continue;} //skip if we found nothing

                //remove any of our own addresses
                $recipient_addresses = array_filter($scraped_emails[0], 'notUs');
                if(empty($recipient_addresses)){continue;} //skip if we found nothing

                //remove any duplicates
                $recipient = array_unique($recipient_addresses);
                $recipient = $recipient[0];
                if(empty($recipient)){continue;} //skip if we found nothing

                $new_row = array(
                    $this->escape_for_csv($insert_time),
                    $this->escape_for_csv($recipient)
                );

                $csv_data .= join(',', $new_row) . "\n";
            }

            //header file
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $table . '_' . date("dmY") . ".csv");
            header('Content-Transfer-Encoding: binary');
            echo $csv_data;
            die();
        } else {
            if ($type === 'bounce') {
                $return['response'] = 'Success: Good news! No email bounce data could be found';
            } else {
                $return['response'] = 'Success: Good news! No email complaint data could be found';
            }
        }
        return $return;
    }


    //get marketing data that will be used later for importing data
    public function get_marketing_data()
    {
        global $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => ''
        );

        //get all subscribers with flag marketing
        $query_string = 'SELECT `subscriber_id`, `business_name`, `contact_name`, `business_department`, `delivery_email`, `support_email`, `marketing_opt_in` FROM `subscriber`';
        $subscribers = $mysqli_db->raw_query($query_string, false);

        //if no subscribers found
        if (empty($subscribers)) {
            $return['response'] = 'No marketing data could be found, please try again later';
            return $return;
        }

        //csv data container
        $csv_data = '';

        //set the csv column titles
        $title_row = array('id', 'business_name', 'contact_name', 'department', 'delivery_email', 'support_email', 'marketing_opt_in');
        $csv_data .= join(',', $title_row) . "\n";

        //build the csv
        foreach ($subscribers as $new_row) {
            $row = array();

            foreach ($new_row as $n) {
                $row[] = $this->escape_for_csv($n);
            }

            $csv_data .= join(',', $row) . "\n";
        }

        //header file
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=marketing_data_' . date("dmY") . ".csv");
        header('Content-Transfer-Encoding: binary');
        echo $csv_data;
        die();
    }
}
