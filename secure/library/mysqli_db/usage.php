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
 * Gotchya: MySQLi has a known bug regarding TEXT and BLOB fields that causes a Fatal Error which is cause by some kind
 * of garbage collection problem where MySQLi tries to read every position of the field rather than just the positions
 * containing data. LONGTEXT for instance has a max of 4GB so MySQLi tries to request 4GB of memory even if there is
 * only a few KB of data contained in the field. Hopefully this bug will be resolved in a later version of PHP.
 *
 * Note: Extra effort has been put into this library because most of our applications lean heavily on MySQL
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//CONTROL //////////////////////////////////////////////////////////////////////////////////////////////////////////////

//turn on all errors (an error log can also be found in the framework root directory)
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(2047);

//set container variable for usage returns
$print = '';

//set table to reference
$test_table = 'mysql_class_test_table'; //this table may not exist, speak to Gordon MacK if you need help.

////////// START: how to instantiate mysqli_db library and connect to a schema

    //database host (usually localhost)
    define('DB_HOST', 'localhost');

    //database username
    define('DB_USER', 'mysql_mount');

    //database password
    define('DB_PASS', 'pGEuA38s');

    //database schema name
    define('DB_NAME', 'framework_test');

    //instantiate mysqli_db and connect to database
    require_once(LOCAL.'mysqli_db.library.php');
    $mysqli_db = new mysqli_db($port = null); //can also specify port if needed

////////// END: how to instantiate mysqli_db library and connect to a schema

////////// START: how to do a quick select all

    //this will get the first 10 rows of data including all columns
    $get_result = $mysqli_db->get($test_table, 10); //defining a row limit is optional eg: 10

    //if the result data is
    if(!empty($get_result)){
        $print .= '<p>Get succeeded.</p>';
        //print_r($get_result); //uncomment to see raw data output
    }else{
        $print .= '<p>Get query failed.</p>';
    }

    /*
        Note: alternative methods of doing a quick get using where clauses and different syntax

        -entirely inline
        $get_result = $mysqli_db->where('test_id', 1)->where('test_string', '4OmGL7DSuHMdwBup')->get($test_table);

        -multiline
        $mysqli_db->where('test_id', 1);
        $mysqli_db->where('test_string', '4OmGL7DSuHMdwBup');
        $get_result = $mysqli_db->get($test_table);
    */

////////// END: how to do a quick select all

////////// START: how to perform an insert query

    $insert_data = array(
        'test_string' => 'HlQxIdwcR1s3miGw',
        'test_integer' => '68',
        'test_long_string' => '
            nbVyEEz2rerPymLKSPOx7kIRDPCnEOUvipHF5RgNhy2ReuVR50YEzdzXxolXuuYB8250eWuqLIj06moetlUowfHl5kcwZXwnSbemcYcNnyB
            AL07gE1ioiCCV53PS4dEW6CMZqG9Da908n3XA6JZy2yTJnolmOKObfsJyBn09GAHiKiKAxYoixuRnbV1loLr8FmwXf3kV8BVXAaUMT0lnYl
            mFirUAjfpHaV0xg2RITx39cbC6giyNERsEg5Zpn7xO46ScTAAdjmf7NYoYLotUblC5U3iSIySLXMfY82sIiU6s7LjysMSg3kbUdMxcZgO05
            ugB6RzAklFmXSC63TH1dfbUbS732iiTR93LJMz8GtnbNqn87IUTAnks4AvxUACbjO8yFzsimxVrE3B0dvQr7sMqA2eIt7iotZrisXyeNhs6
        ',
        'test_epoch' => '1380602245',
        'test_float' => '154.56'
    );

    //this will insert the above data into the table using the keys as the column names and the values as the data.
    $insert_result = $mysqli_db->insert($test_table, $insert_data);
    if($insert_result){
        $print .= '<p>Insert query succeeded.</p>';
    }else{
        $print .= '<p>Insert query failed.</p>';
    }

////////// END: how to perform an insert query

////////// START: how to get ID of last record inserted

    //this will grab the id of the last inserted row as an integer
    $insert_id = $mysqli_db->get_insert_id();
    if($insert_id > 0){
        $print .= '<p>Get last insert id succeeded.</p>';
    }else{
        $print .= '<p>Get last insert id failed.</p>';
    }

////////// END: how to get ID of last record inserted

////////// START: how to perform an update query

    $update_data = array(
        'test_string' => '4OmGL7DSuHMdwBup',
        'test_integer' => '68',
        'test_long_string' => '
                L32Z8HKLVdLAqBMhIOl00q7mRm4lDdEkponIVEAvGYsw6aUFmt3mFaVIBKXdec7CnwOxwKh84X4CfR13oSs0jYCMYL6mtHbvbKbJJ3v
                nbVyEEz2rerPymLKSPOx7kIRDPCnEOUvipHF5RgNhy2ReuVR50YEzdzXxolXuuYB8250eWuqLIj06moetlUowfHl5kcwZXwnSbemcYc
                AL07gE1ioiCCV53PS4dEW6CMZqG9Da908n3XA6JZy2yTJnolmOKObfsJyBn09GAHiKiKAxYoixuRnbV1loLr8FmwXf3kV8BVXAaUMT0
                mFirUAjfpHaV0xg2RITx39cbC6giyNERsEg5Zpn7xO46ScTAAdjmf7NYoYLotUblC5U3iSIySLXMfY82sIiU6s7LjysMSg3kbUdMxcZ
            ',
        'test_epoch' => '1380607275',
        'test_float' => '962.67'
    );

    //a where clause has to be used when using the update method
    $mysqli_db->where('test_id', $insert_id); //using the ID of the record that was just inserted above
    //this will update the record using above data.
    $update_result = $mysqli_db->update($test_table, $update_data);

    if($update_result){
        $print .= '<p>Update query succeeded.</p>';
    }else{
        $print .= '<p>Update query failed.</p>';
    }

////////// END: how to perform an update query

////////// START: how to perform a delete query

    //a where clause has to be used when using the delete method
    $mysqli_db->where('test_id', $insert_id); //using the ID of the record that was just inserted above
    //this will delete the record
    $delete_result = $mysqli_db->delete($test_table);

    if($delete_result){
        $print .= '<p>Delete query succeeded.</p>';
    }else{
        $print .= '<p>Delete query failed.</p>';
    }

////////// END: how to perform a delete query

////////// START: how to perform a generic query

    //this will let you run any simple query, for the times when the quick functions above restrict you too much.
    $generic_result = $mysqli_db->query('SELECT * FROM `'.$test_table.'`');

    if(!empty($generic_result)){
        $print .= '<p>Generic query succeeded.</p>';
        //print_r($generic_result); //uncomment to see raw data output
    }else{
        $print .= '<p>Generic query failed.</p>';
    }

////////// END: how to perform a generic query

////////// START: how to perform a raw query

    //for the times when you need extra flexibility, be careful though, this function does nothing but run your query, make sure your data is safe.
    $raw_result = $mysqli_db->raw_query('SELECT * FROM `'.$test_table.'` WHERE `test_id` = 1 AND `test_string` = "4OmGL7DSuHMdwBup"');

    if(!empty($raw_result)){
        $print .= '<p>Raw query succeeded.</p>';
        //print_r($raw_result); //uncomment to see raw data output
    }else{
        $print .= '<p>Raw query failed.</p>';
    }

////////// END: how to perform a raw query


////////// START: how to perform a prepared query

//set bind parameters
$bind_params = array(0);

//this will let you run any prepared statement query
$prepared_result = $mysqli_db->raw_query('SELECT * FROM `'.$test_table.'` WHERE `test_id` > ?', $bind_params);

if(!empty($prepared_result)){

    $print .= '<p>Prepared query succeeded.</p>';
    //print_r($prepared_result); //uncomment to see raw data output
}else{
    $print .= '<p>Prepared query failed.</p>';
}

////////// END: how to perform a generic query

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: mysqli_db.library.php');
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

        <p>If you're not sure what this page is for, please open the <a href="<?php echo LOCAL_DIR; ?>__README.txt" title="README FILE">README</a> or speak to Gordon MacK</p>

        <hr />

        <h2>Usage Results</h2>

        <?php if(isset($print) && !empty($print)){echo $print;} ?>
    </body>
</html>