<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // Sentry Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: End user tracking and logging
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class sentry{

        private $_log_table = 'sentry_log';
        private $_session_id = ''; //set a container to store the session id

        //the moment the sentry library is called, look for / set up a tracking session
        public function __construct(){

            //bring dependant libraries into scope
            global $mysqli_db, $validate;

            //check that the sentry table exists
            $table_check = $mysqli_db->table_exists($this->_log_table);
            if(! $table_check){
                //no need to check response, hard errors on fail
                $this->create_log_table();
            }else{
                //if the table exists, run a clean
                $this->clean_log_table();
            }

            //set the session id
            $this->_session_id = session_id();

            //collect and set data array, nearly all of this data can be spoofed, but the more we monitor the better
            if(isset($_SERVER['HTTP_REFERER'])){
                $referer = $_SERVER['HTTP_REFERER'];
            }else{
                $referer = '';
            }

            if(isset($_POST) && !empty($_POST)){
                //check if array is multi-dimensional (don't record it if it is)
                if(count($_POST) == count($_POST, COUNT_RECURSIVE)){

                    //save post array to local variable so we can manipulate it without damaging data
                    $haystack = $_POST;

                    //set an array of key needles to ignore
                    $needles = array('username', 'user', 'password', 'pass');

                    //attempt to ignore username and password data in the post array by looping through a needle/haystack set
                    foreach($haystack as $key => $value){
                        foreach($needles as $n){
                            //if a key matches a needle, drop the var
                            if($key == $n){
                                unset($haystack[$key]);
                            }
                        }
                    }

                    //convert the array to a JSON string
                    $post_array = $validate->encode_json($haystack);
                }else{
                    $post_array = '';
                }
            }else{
                $post_array = '';
            }

            if(isset($_GET) && !empty($_GET)){
                //convert the array to a JSON string
                $get_array = $validate->encode_json($_GET);
            }else{
                $get_array = '';
            }

            $log_data = array(
                'message' => 'OK: Connection accepted.', 'session_id' => $this->_session_id,
                'request_time' => $_SERVER['REQUEST_TIME'], 'url' => $this->get_complete_url(), 'referer' => $referer,
                'end_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'end_user_language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'], 'end_user_ip' => $_SERVER['REMOTE_ADDR'],
                'end_user_port' => $_SERVER['REMOTE_PORT'],
                'post_array' => $post_array,
                'get_array' => $get_array
            );

            //validate the data carefully
            $type_array['message'] = 'string';
            $type_array['session_id'] = 'string_int';
            $type_array['sentry_id'] = 'string_int';
            $type_array['request_time'] = 'int';
            $type_array['url'] = 'url';
            $type_array['referer'] = 'url';
            $type_array['end_user_agent'] = 'string';
            $type_array['end_user_language'] = 'string';
            $type_array['end_user_ip'] = 'ip';
            $type_array['end_user_port'] = 'int';
            $type_array['post_array'] = 'string';
            $type_array['get_array'] = 'string';

            $sanitised_data = $validate->sanitise_array($log_data, $type_array, true);

            //make sure the data is safe before the insert.
            if($sanitised_data){
                //insert data into the sentry log table, no need to listen for response
                $mysqli_db->insert($this->_log_table, $sanitised_data);
            }else{
                //if the end user's data fails to validate, kick them out.
                $error_data = array(
                    'message' => 'ERROR: Potentially malicious data detected, connection refused, user redirected.'
                );
                //insert data into the sentry log table, no need to listen for response
                $mysqli_db->insert($this->_log_table, $error_data);
                //redirect the user to the root of the current site
                header('Location: /');
                //if the header doesn't work then we will just kill the application.
                die();

            }
        }


        //creates the log table if it doesn't exist already, saves some setup time.
        private function create_log_table(){

            //bring dependant libraries into scope
            global $mysqli_db;

            //try to create the sentry logging table
            $create = $mysqli_db->raw_query('
            CREATE TABLE `'.$this->_log_table.'` (
                    `'.$this->_log_table.'_id` int(12) NOT NULL AUTO_INCREMENT,
                    `message` text COLLATE utf8_bin NOT NULL,
                    `session_id` varchar(26) COLLATE utf8_bin NOT NULL,
                    `request_time` int(12) NOT NULL,
                    `url` varchar(2000) COLLATE utf8_bin NOT NULL,
                    `referer` varchar(2000) COLLATE utf8_bin NOT NULL,
                    `end_user_agent` varchar(4096) COLLATE utf8_bin NOT NULL,
                    `end_user_language` varchar(999) COLLATE utf8_bin NOT NULL,
                    `end_user_ip` varchar(39) COLLATE utf8_bin NOT NULL,
                    `end_user_port` varchar(5) COLLATE utf8_bin NOT NULL,
                    `post_array` text COLLATE utf8_bin NOT NULL,
                    `get_array` text COLLATE utf8_bin NOT NULL,
                PRIMARY KEY (`'.$this->_log_table.'_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
        ');

            if($create){
                return true;
            }else{
                //if we end up here it's a setup error so trigger error
                trigger_error('Sentry logging table does not exist and could not be created.', E_USER_ERROR);
                return false;
            }
        }


        //wipes old records from the log table so we don't end up with bloat problems
        private function clean_log_table(){

            //bring dependant libraries into scope
            global $mysqli_db;

            //delete any records that are more than 2 days old
            $week_ago = time() - 172800;

            //perform the wipe, no need to listen for response
            $mysqli_db->raw_query('DELETE FROM `'.$this->_log_table.'` WHERE `request_time` < '.$week_ago, false);

            return true;
        }


        //returns the entire current URL that the script is running on in an array of elements
        public function get_complete_url($array_format = false){

            //generate a complete URL string
            $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
            $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
            $protocol = substr($sp, 0, strpos($sp, "/")).$s;
            $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
            $string_url = $protocol."://".$_SERVER['HTTP_HOST'].$port.$_SERVER['REQUEST_URI'];

            //convert the URL string into an array of elements if set
            if($array_format){
                $array_url = parse_url($string_url);
                return $array_url;
            }else{
                return $string_url;
            }
        }

    }
