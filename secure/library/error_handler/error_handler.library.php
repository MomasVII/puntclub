<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // Error Handler Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: A library to catch errors and send them to a database table
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class error_handler{

        private $_error_table = 'error_log';
        private $_mysqli_db = null;


        //set up the error handling basics on load
        public function __construct(){

            //modify ini
            ini_set('display_errors', 0);

            /*
                we can't use our usual mysql library because there is a high chance that it has been destroyed before
                any error methods are called.
             */

            $this->_mysqli_db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            //check if the sessions table exists, if it doesn't, create it.
            $check_query = $this->_mysqli_db->query('SHOW TABLES LIKE "'.$this->_error_table.'"');
            $check_table = $check_query->fetch_row();
            if(empty($check_table[0])){
                $this->create_log_table();
            }

            //if not set to 0 fatal errors won't go into stack
            error_reporting(0);
            //set the error handler function
            set_error_handler(array($this, 'error_handler'));
            //this is a trick to catch fatal errors that aren't sent to the normal error handler
            register_shutdown_function(array($this, 'fatal_error_handler'));
        }


        public function __destruct(){

            //close db connection
            $thread = $this->_mysqli_db->thread_id;
            $this->_mysqli_db->kill($thread);
            $this->_mysqli_db->close();
        }


        //general error handler
        public function error_handler($type, $message, $file, $line){

            //check if errors are set to be silenced
            if(!SILENCE_ERRORS){
                //sanitise the data and insert it into the table
                $type = $this->_mysqli_db->real_escape_string($type);
                $message = $this->_mysqli_db->real_escape_string($message);
                $file = $this->_mysqli_db->real_escape_string($file);
                $line = $this->_mysqli_db->real_escape_string($line);

                $sql = 'INSERT INTO `'.$this->_error_table.'` (`error_time`, `type`, `message`, `file`, `line`) VALUES ('.time().', '.$type.', "'.$message.'", "'.$file.'", '.$line.')';
                $this->_mysqli_db->query($sql);
            }
        }


        //function to catch fatal errors
        public function fatal_error_handler(){

            $last_error = error_get_last();
            //check if the last error was an error
            if($last_error['type'] === E_ERROR){
                //pass the fatal error to the error handler
                $this->error_handler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
            }
        }


        //creates the log table if it doesn't exist already, saves some setup time.
        private function create_log_table(){

            //try to create the session cache table
            $create = $this->_mysqli_db->query('
            CREATE TABLE `'.$this->_error_table.'` (
                    `'.$this->_error_table.'_id` int(12) NOT NULL AUTO_INCREMENT,
                    `error_time` int(12) NOT NULL,
                    `type` tinyint(6) NOT NULL,
                    `message` text COLLATE utf8_bin NOT NULL,
                    `file` text COLLATE utf8_bin NOT NULL,
                    `line` bigint(20) NOT NULL,
                PRIMARY KEY (`'.$this->_error_table.'_id`),
                UNIQUE KEY `'.$this->_error_table.'_id_UNIQUE` (`'.$this->_error_table.'_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
        ');

            if($create){
                return true;
            }else{
                //turn error display back on and trigger an error
                ini_set('display_errors', 1);
                error_reporting(E_ALL);
                trigger_error('Error log table does not exist and could not be created.', E_USER_ERROR);
                return false;
            }
        }

    }