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
 *
 * Note: This library has been developed over a long period of time and combines a number of developer's code, because of
 * this, you may notice some inconsistencies.
 *
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//CONTROL //////////////////////////////////////////////////////////////////////////////////////////////////////////////

//turn on all errors (an error log can also be found in the framework root directory)
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(2047);

//set container variable for usage returns
$print = '';

////////// START: how to instantiate csv

    //instantiate shortcut
    require_once(LOCAL.'csv.library.php');
    $csv = new csv();

////////// END: how to instantiate csv

////////// START: how to do a simple csv read with a defined delimiter

    //set the delimiter (UTF-8 only)
    $csv->delimiter = ','; //if the delimiter was TAB you would use '\t' as the pattern

    //parse the csv
    $csv->parse('test.csv');

    //save the results to a variable in the local scope
    $result = $csv->data;

    if(!empty($result)){
        $print .= '<p>CSV simple read with defined delimiter succeeded.</p>';
        //print_r($result); //uncomment to see raw data output
    }else{
        $print .= '<p>CSV simple read with defined delimiter failed.</p>';
    }

////////// END: how to do a simple csv read with a defined delimiter

////////// START: how to do a simple csv read and automatically find the delimiter

    //parse the csv and try to find the delimiter
    $csv->auto('test.csv');

    //save the results to a variable in the local scope
    $result = $csv->data;

    if(!empty($result)){
        $print .= '<p>CSV simple read with automatically found delimiter succeeded.</p>';
        //print_r($result); //uncomment to see raw data output
    }else{
        $print .= '<p>CSV simple read with automatically found delimiter failed.</p>';
    }

////////// END: how to do a simple csv read and automatically find the delimiter

////////// START: how to do a csv read with descriptive conditions

    //set a condition (inspect the library to understand syntax or speak to Gordon)
    $csv->conditions = 'author does not contain dan brown';
    //$csv->conditions = 'rating < 4 OR author is John Twelve Hawks';
    //$csv->conditions = 'rating > 4 AND author is Dan Brown';
    //$csv->conditions = 'title contains paperback OR title contains hardcover';

    //parse the csv and try to find the delimiter
    $csv->auto('test.csv');

    //save the results to a variable in the local scope
    $result = $csv->data;

    if(!empty($result)){
        $print .= '<p>CSV read with descriptive conditions succeeded.</p>';
        //print_r($result); //uncomment to see raw data output
    }else{
        $print .= '<p>CSV read with descriptive conditions failed.</p>';
    }

////////// END: how to do a csv read with descriptive conditions

////////// START: how to do a csv and encoding conversion

    //parse the csv and try to find the delimiter
    $csv->auto('test.csv');

    //change the character encoding from 16 to 8
    $csv->encoding('UTF-16', 'UTF-8');

    //save the results to a variable in the local scope
    $result = $csv->data;

    if(!empty($result)){
        $print .= '<p>CSV read and encoding conversion succeeded.</p>';
        //print_r($result); //uncomment to see raw data output
    }else{
        $print .= '<p>CSV read and encoding conversion failed.</p>';
    }

////////// END: how to do a csv and encoding conversion

////////// START: how to modify data inside a CSV file (only recommended when you know the exact structure of the file.)

    //set the sort order, this column will be used to match content (pseudo where clause, only works when field names are found)
    $csv->sort_by = 'id';

    //set the delimiter, always manually set the delimiter when altering data.
    $csv->delimiter = ','; //if the delimiter was TAB you would use '\t' as the pattern

    //parse the csv
    $csv->parse('test.csv');

    //update the row data where id is equal to 11, you must define -every- column or the CSV will fall apart.
    $csv->data[5] = array('id' => '5', 'rating' => '4', 'title' => 'I like CSV documents', 'author' => 'raremedia', 'type' => 'Book', 'asin' => '94289558', 'tags' => 'Rare is the best');

    //if a match isn't found, the data will just be appended to the end of the document, it's worth being careful
    $result = $csv->save();

    if($result){
        $print .= '<p>CSV data modification succeeded.</p>';
        //print_r($result); //uncomment to see raw data output
    }else{
        $print .= '<p>CSV data modification failed.</p>';
    }

////////// END: how to modify data inside a CSV file (only recommended when you know the exact structure of the file.)

////////// START: how to append data to the end of a CSV file

    //set the data you want to append as and array (must be in order)
    $data_array = array('id' => '99', 'rating' => '2', 'title' => 'Appended data', 'author' => 'raremedia', 'type' => 'Junk', 'asin' => '3456234521', 'tags' => 'appendappendappendappend');


    //append the data to the csv document and save
    $result = $csv->save('test.csv', $data_array, true);

    if($result){
        $print .= '<p>CSV data append succeeded.</p>';
        //print_r($result); //uncomment to see raw data output
    }else{
        $print .= '<p>CSV data append failed.</p>';
    }

////////// END: how to append data to the end of a CSV file

////////// START: how to convert an array to CSV and header file to download

    //optionally set the field array, if not set the first line of the CSV will be blank
    $field_array =  array('id' , 'rating', 'title', 'author', 'type', 'asin', 'tags');

    //set the data you want to append as and array (must be in order)
    $data_array = array(
                        array('id' => '1', 'rating' => '3', 'title' => 'Title 1', 'author' => 'raremedia', 'type' => 'Test', 'asin' => '1', 'tags' => 'tag1'),
                        array('id' => '2', 'rating' => '2', 'title' => 'Title 2', 'author' => 'raremedia', 'type' => 'Test', 'asin' => '2', 'tags' => 'tag2'),
                        array('id' => '3', 'rating' => '1', 'title' => 'Title 3', 'author' => 'raremedia', 'type' => 'Test', 'asin' => '3', 'tags' => 'tag3')
    );


    //$csv->output(true, 'test.csv', $data_array, $field_array);

    $print .= '<p>CSV header skipped.</p>';

////////// END: how to convert an array to CSV and header file to download

////////// START: how to convert an array to CSV and save the file to the server

    //optionally set the field array, if not set the first line of the CSV will be blank
    $field_array =  array('id' , 'rating', 'title', 'author', 'type', 'asin', 'tags');

    //set the data you want to append as and array (must be in order)
    $data_array = array(
        array('id' => '1', 'rating' => '3', 'title' => 'Title 1', 'author' => 'raremedia', 'type' => 'Test', 'asin' => '1', 'tags' => 'tag1'),
        array('id' => '2', 'rating' => '2', 'title' => 'Title 2', 'author' => 'raremedia', 'type' => 'Test', 'asin' => '2', 'tags' => 'tag2'),
        array('id' => '3', 'rating' => '1', 'title' => 'Title 3', 'author' => 'raremedia', 'type' => 'Test', 'asin' => '3', 'tags' => 'tag3')
    );

    //$csv->save('written_by_php.csv', $data_array, false, $field_array);

    $print .= '<p>CSV save skipped.</p>';

////////// END: how to convert an array to CSV and save the file to the server

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: csv.library.php');
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

        <p>If you're not sure what this page is for, please open the <a href="<?php echo LOCAL; ?>__README.txt" title="README FILE">README</a> or speak to Gordon MacK</p>

        <hr />

        <h2>Usage Results</h2>

        <?php if(isset($print) && !empty($print)){echo $print;} ?>

    </body>
</html>