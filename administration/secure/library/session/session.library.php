<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // Session Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: Database sessions and general session handling
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class session
    {

        //define vars
        private $_session_table = 'session_cache';
        private $_mysqli_db = null;

        //set up the session basics on load
        public function __construct()
        {

            //modify ini
            //if we are running in https mode, enable secure cookies
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', 1);
            } else {
                ini_set('session.cookie_secure', 0);
            }
            ini_set('session.cookie_httponly', 1); //stops any client-side scripts getting access to cookie data
            ini_set('session.use_only_cookies', 1); //make sure only cookies are used for client side session data
            //PEN-TEST MOD: change 'cookie_lifetime' from 0(abort) to SESSION_LIFE constant
            ini_set('session.cookie_lifetime', SESSION_LIFE); //make session cookies expire when user's browser is closed
            ini_set('session.gc_maxlifetime', SESSION_LIFE); //seconds before dormant session data is seen as garbage
            if (!(session_status() === PHP_SESSION_ACTIVE)) {
                ini_set('session.use_trans_sid', 0); //stop session_id being transmitted in URL
            }

            /*
                we can't use our usual mysql library because it destroys itself before session handlers are ever called
                making it unavailable in this scope, instead we have to interface with mysqli separately.
             */

            $this->_mysqli_db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            //check if the sessions table exists, if it doesn't, create it.
            $check_query = $this->_mysqli_db->query('SHOW TABLES LIKE "'.$this->_session_table.'"');
            $check_table = $check_query->fetch_row();
            if (empty($check_table[0])) {
                $this->create_cache_table();
            }

            //check if a session has already been started, start it if not.
            if (session_status() === PHP_SESSION_NONE) {
                //set the session name
                session_name(SESSION_NAME);

                //stop default session behaviour and set handler functions
                session_set_save_handler(
                    array($this, 'open'),
                    array($this, 'close'),
                    array($this, 'read'),
                    array($this, 'write'),
                    array($this, 'destroy'),
                    array($this, 'garbage_collector')
                );

                //start the session
                session_start();
            }

            //manually run the garbage collector to make sure sessions older than 45 minutes are removed
            $this->garbage_collector();
        }


        public function open()
        {

            //attempt to set the session id
            $session_id = session_id();

            //check if a session id is set
            if (! empty($session_id)) {
                //clean the session_id just to be on the safe side
                $session_id = $this->_mysqli_db->real_escape_string($session_id);
                //check if a db session record already exists for the current session id
                $session_query = $this->_mysqli_db->query('SELECT COUNT(*) FROM `'.$this->_session_table.'` WHERE `session_id` = "'.$session_id.'" LIMIT 1');
                $check_session = $session_query->fetch_row();

                if ($check_session[0] > 0) {
                    //session exists, update the time in order to track the last activity
                    $this->_mysqli_db->query('UPDATE `'.$this->_session_table.'` SET `time` = '.time().' WHERE `session_id` = "'.$session_id.'"');
                }
            }

            return true;
        }


        public function close()
        {

            /*
            this worked fine on PHP5.6 but 500's on PHP7.1 when logging into the admin console
            sqli connections close themselves and this is the admin so we don't need to worry about recovering every possible resource immediately

            close db connection
            $thread = $this->_mysqli_db->thread_id;
            $this->_mysqli_db->kill($thread);
            $this->_mysqli_db->close();
            */

            return true;
        }


        public function read($session_id)
        {

            //select matching session data
            $session_id = $this->_mysqli_db->real_escape_string($session_id);
            $session_query = $this->_mysqli_db->query('SELECT `session_data` FROM `'.$this->_session_table.'` WHERE `session_id` = "'.$session_id.'" LIMIT 1');
            $session_data = $session_query->fetch_row();

            //return session data if found, otherwise return a blank string
            if (! empty($session_data[0])) {
                return $session_data[0];
            } else {
                return 'TEST READ BUG';
            }
        }


        public function write($session_id, $session_data)
        {

            //update the matching session record and sanitise session data
            $session_id = $this->_mysqli_db->real_escape_string($session_id);
            $session_data = $this->_mysqli_db->real_escape_string($session_data);
            $this->_mysqli_db->query('INSERT INTO `'.$this->_session_table.'` (`session_id`, `time`, `session_data`) VALUES ("'.$session_id.'", '.time().', "'.$session_data.'") ON DUPLICATE KEY UPDATE `session_data` = "'.$session_data.'"');

            return true;
        }


        public function destroy($session_id)
        {

            //if the session needs to be destroyed wipe everything
            $session_id = $this->_mysqli_db->real_escape_string($session_id);
            $this->_mysqli_db->query('DELETE FROM `'.$this->_session_table.'` WHERE `session_id` = "'.$session_id.'"');

            return true;
        }


        public function garbage_collector()
        {

            /*
                sometimes this doesn't run as often as it should, so it's called in the constructor as well, we can do this
                because we track user activity via the update time field in the session table.
            */

            //set the lifetime relative to now
            $relative_lifetime = time() - SESSION_LIFE;

            //perform the delete
            $this->_mysqli_db->query('DELETE FROM `'.$this->_session_table.'` WHERE `time` < '.$relative_lifetime);
        }


        //creates the cache table if it doesn't exist already, saves some setup time.
        private function create_cache_table()
        {

            //try to create the session cache table
            $create = $this->_mysqli_db->query('
                CREATE TABLE `'.$this->_session_table.'` (
                        `session_id` varchar(255) NOT NULL,
                        `time` int(12) NOT NULL,
                        `session_data` text NOT NULL,
                    PRIMARY KEY (`session_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            ');

            if ($create) {
                return true;
            } else {
                //if we end up here it's a setup error so trigger error
                trigger_error('Sessions cache table does not exist and could not be created.', E_USER_ERROR);
                return false;
            }
        }
    }
