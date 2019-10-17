<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // Shortcut Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: Handy function dictionary
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class shortcut{

        //remove any get variables from the URI string
        public function clean_uri($uri){
            $uri_parts = explode('?', $uri, 2);

            return $uri_parts[0];
        }

        //relative redirect from current directory.
        public function relative_header($location){

            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['REQUEST_URI']), '/\\');
            header('Location: http://'.$host.$uri.'/'.$location);
            die();
        }

        //javascript redirect, useful for when headers have already been sent.
        public function js_redirect($url){

            //this method of redirection is not reliable or secure, avoid using this in production code.
            echo '<script type=\'text/javascript\'>window.location="'.$url.'";</script>';
        }


        //javascript alert, works from within class files (although not W3C compliant), helps to find hard to reach errors
        public function js_alert($string){

            echo '<script type=\'text/javascript\'>alert("'.$string.'");</script>';
        }


        //shorten a string and add a tail, return the original string if it's shorter than the limit.
        public function truncate_string($string, $limit, $tail = '...'){

            //check if anything actually needs to be done.
            if(strlen($string) > $limit){
                $leave = $limit - strlen($tail);
                return substr_replace($string, $tail, $leave);
            }else{
                return $string;
            }
        }


        //generate a readable random string
        public function random_string($length){

            //specify character set, difficult to discern characters removed.
            $characters = '23456789abcdefghijkmnpqrstuvwxyz';
            $string = '';

            for($p = 0; $p < $length; $p ++){
                $string .= $characters[mt_rand(0, strlen($characters) - 1)];
            }

            return $string;
        }


        //translate an epoch into english describing the epoch relative to current second
        public function time_relative_descriptive($time){

            //define time increments
            $second = 1;
            $minute = 60 * $second;
            $hour = 60 * $minute;
            $day = 24 * $hour;
            $month = 30 * $day;

            //calculate time difference
            $delta = time() - $time;

            //change the language depending on future or past
            if(time() > $time){
                $qualifier = 'ago';
            }else{
                $qualifier = 'to go';
                //future times calculate as a negative, remove it.
                $delta = abs($delta);
            }

            //begin if switches, the output becomes less and less specific the further past the time.
            if($time == time()){
                return 'now';
            }elseif($delta < 1 * $minute){
                return $delta == 1 ? 'one second '.$qualifier : $delta.' seconds '.$qualifier;
            }elseif($delta < 2 * $minute){
                return 'a minute '.$qualifier;
            }elseif($delta < 45 * $minute){
                return floor($delta / $minute).' minutes '.$qualifier;
            }elseif($delta < 90 * $minute){
                return 'an hour '.$qualifier;
            }elseif($delta < 24 * $hour){
                return floor($delta / $hour).' hours '.$qualifier;
            }elseif($delta < 48 * $hour){
                return 'one day '.$qualifier;
            }elseif($delta < 30 * $day){
                return floor($delta / $day).' days '.$qualifier;
            }elseif($delta < 12 * $month){
                $months = floor($delta / $day / 30);
                return $months <= 1 ? 'one month '.$qualifier : $months.' months '.$qualifier;
            }else{
                $years = floor($delta / $day / 365);
                return $years <= 1 ? 'one year '.$qualifier : $years.' years '.$qualifier;
            }
        }


        //translate an epoch into array describing the epoch hours, mins and seconds, not relative
        public function time_counter($seconds){

            //extract hours
            $hours = floor($seconds / (60 * 60));

            //extract minutes
            $divisor_for_minutes = $seconds % (60 * 60);
            $minutes = floor($divisor_for_minutes / 60);

            //extract the remaining seconds
            $divisor_for_seconds = $divisor_for_minutes % 60;
            $seconds = ceil($divisor_for_seconds);

            //return the final array
            $obj = array(
                'h' => (int)$hours, 'm' => (int)$minutes, 's' => (int)$seconds,
            );

            return $obj;
        }


        //feed in any int and this will return the int with th/st/nd/rd/th appended, int is turned into a string.
        public function append_ordinal($int){

            $test = abs($int) % 10;
            $ext = ((abs($int) % 100 < 21 && abs($int) % 100 > 4) ? 'th' : (($test < 4) ? ($test < 3) ? ($test < 2) ? ($test < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));

            return $int.$ext;
        }


        //finds and returns memory usage as an array
        public function system_memory_usage(){

            //set container variable
            $return = array();

            //get the time that this method was called so we can pinpoint the data
            $return['executed_epoch'] = time();
            //get current memory usage in kb
            $return['current_memory_usage_kb'] = round(memory_get_usage() / 1024, 2);
            //get peak memory usage in kb
            $return['peak_memory_usage_kb'] = round(memory_get_peak_usage() / 1024, 2);
            //get current memory usage in mb
            $return['current_memory_usage_mb'] = round($return['current_memory_usage_kb'] / 1024, 2);
            //get peak memory usage in mb
            $return['peak_memory_usage_mb'] = round($return['peak_memory_usage_kb'] / 1024, 2);

            return print_r($return);
            //be sure to use this function using register_shutdown_function(array('shortcut', 'system_memory_usage'))
            //never use this function in production code, quality control and diagnostic purposes only
        }


        //finds and returns user time and cpu time consumed as an array
        public function system_time_usage(){

            //set container variable
            $return = array();

            $cpu_info = getrusage();

            //get the time that this method was called so we can pinpoint the data
            $return['executed_epoch'] = time();
            //get the user time (time it took for the entire process to complete in real time)
            $return['real_time_usage'] = ($cpu_info['ru_utime.tv_sec'] + $cpu_info['ru_utime.tv_usec'] / 1000000).' seconds';
            //get the cpu time (cpu time that was used to complete the entire process)
            $return['cpu_time_usage'] = ($cpu_info['ru_utime.tv_sec'] + $cpu_info['ru_utime.tv_usec'] / 1000000).' seconds';

            return print_r($return);
            //be sure to use this function using register_shutdown_function(array('shortcut', 'system_time_usage'))
            //never use this function in production code, quality control and diagnostic purposes only
        }
    }