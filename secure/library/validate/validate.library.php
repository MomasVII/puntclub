<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // Validate Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: Data Validation
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class validate{

        private $_restricted_strings = array(
            'java', 'script', 'src', 'url', 'href', 'true', 'false', 'drop'
        ); //default array of restricted words or strings for string filter functionality

        //function to check that the referer matches the domain that the script is/should be running on. A referer can be spoofed, but it's worth checking anyway.
        public function check_referer(){

            //set the domain that the script is running on
            $location_domain = $_SERVER['HTTP_HOST'];

            //check if there is a referer set, if not return false
            if(isset($_SERVER['HTTP_REFERER']) && ! empty($_SERVER['HTTP_REFERER'])){
                //parse the referer URL and get the host chunk
                $referer = parse_url($_SERVER['HTTP_REFERER']);
                $referer_domain = $referer['host'];

                //if the referer matches the script location, return true
                if($referer_domain == $location_domain){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }


        //function to do a quick clean of an array or variable, not a golden bullet but a good place to start
        public function quick_clean($dirty){

            //check if it's an array or not
            if(! is_array($dirty)){
                //do some cleanup before sanitising
                $dirty = stripslashes(trim($dirty));
                //run an aggressive regex replace, this can ruin some data types eg: email addresses
                $less_dirty = preg_replace('/[^\w\.\-\&\' ]/', '', $dirty);
            }else{
                $less_dirty = array();
                foreach($dirty as $key => $value){
                    //do some cleanup before sanitising
                    $value = stripslashes(trim($value));
                    //run an aggressive regex replace, this can ruin some data types eg: email addresses
                    $value = preg_replace('/[^\w\.\-\&\' ]/', '', $value);
                    $less_dirty[$key] = $value;
                }
            }

            return $less_dirty;
        }


        //compress a string, reduces size by up to 50% most of the time
        public function compress_string($string){

            //compress the string, no sanitising will be done because we can't predict the string data type
            $compressed = gzcompress($string);

            return $compressed;
        }


        //decompress a string, will return a string to it's original state
        public function decompress_string($compressed){

            //decompress the string
            $decompressed = gzuncompress($compressed);

            return $decompressed;
        }


        //encode a string into base64
        public function encode_base64($string){

            //convert the string, no sanitising will be done because we can't predict the string data type
            $encoded = base64_encode($string);

            return $encoded;
        }


        //decode a string from base64
        public function decode_base64($encoded){

            //return the string to plain
            $decoded = base64_decode($encoded);

            return $decoded;
        }


        //encode and sanitise an array into JSON, handy for storing complex variables in a flat file or single table column.
        public function encode_json($array){

            //do a friendly string sanitise using array map to do a callback to $this->sanitise_string()
            $clean_array = array_map(array($this, 'sanitise_string'), $array);

            $encoded = json_encode($clean_array);

            return $encoded;
        }


        //decode a JSON string from an array, assumes you want an array instead of objects.
        public function decode_json($encoded, $array = true){

            //convert the json string back into an array
            $decoded = json_decode($encoded, $array);

            return $decoded;
        }


        //encode and sanitise HTML string, handy for storing WYSIWYG content in a database.
        public function encode_html($dirty){

            //check if magic quotes are on
            if(! get_magic_quotes_runtime()){
                //remove any existing slashes and then add them
                $dirty = addslashes(stripslashes($dirty));

            }

            //encode HTML special characters
            $clean = htmlentities($dirty, ENT_QUOTES, 'UTF-8');

            return $clean;
        }


        //decode an encoded HTML string
        public function decode_html($encoded){

            //remove html special character encoding
            $encoded = html_entity_decode($encoded, ENT_QUOTES, 'UTF-8');
            //remove slashes
            $decoded = stripslashes($encoded);

            return $decoded;
        }


        //encode a URL string, handy for database storage
        public function encode_url($raw_url){

            $encoded = rawurlencode($raw_url);

            return $encoded;
        }


        //decode an encoded URL string
        public function decode_url($encoded){

            $decoded = rawurldecode($encoded);

            return $decoded;
        }


        //camel case a string based on spaces, handy for formatting names.
        public function camel_case($dirty){

            //force the string to lower so that even all uppercase strings get parsed correctly
            $clean = ucwords(strtolower(trim($dirty)));

            return $clean;
        }


        //validate an IP address (IPV6 compatible)
        private function sanitise_ip($dirty){

            $clean = filter_var($dirty, FILTER_VALIDATE_IP);

            return $clean;
        }


        //validate a integer, extra setting for allowing empty
        private function sanitise_int($dirty){

            if(! empty($dirty)){
                $clean = filter_var($dirty, FILTER_VALIDATE_INT);

                return $clean;
            }else{
                return false;
            }
        }


        //validate alpha numeric string, there is no filter_var for this so instead emulate the same return behaviour.
        private function sanitise_string_int($dirty){

            //check if var is a string and only contains letters and/or numbers
            if(is_string($dirty) && ctype_alnum($dirty)){
                $clean = $dirty;
            }else{
                $clean = false;
            }

            return $clean;
        }


        //validate an email address, only works for standard UTF-8 characters
        private function sanitise_email($dirty){

            $clean = filter_var($dirty, FILTER_VALIDATE_EMAIL);

            return $clean;
        }


        //validate an url address, only works for standard UTF-8 characters
        private function sanitise_url($dirty){

            $clean = filter_var($dirty, FILTER_VALIDATE_URL);

            return $clean;
        }


        //validate a plain string, this is fairly friendly and allows most UTF-8 characters
        private function sanitise_string($dirty){

            if(strlen($dirty) > 0){

                //PEN-TEST MOD: add 'FILTER_SANITIZE_STRING'
                $clean = filter_var($dirty, FILTER_SANITIZE_STRING);
                $clean = html_entity_decode(trim($clean), ENT_QUOTES, 'UTF-8');
                $clean = htmlspecialchars($clean, ENT_QUOTES, 'UTF-8');

                return $clean;
            }else{
                return false;
            }
        }


        //validate a float
        private function sanitise_float($dirty){

            $clean = filter_var($dirty, FILTER_VALIDATE_FLOAT);

            return $clean;
        }


        //restricted words/strings filter function ($restricted_strings), extra option to set a custom string array
        private function sanitise_restricted($dirty, $custom_strings = array()){

            //set a return container with dirty var, this will be overwritten by false if the regex trigger is fired.
            $return = $dirty;

            //if a custom string set has been defined, use those instead.
            if(! empty($custom_strings)){
                $search_strings = $custom_strings;
            }else{
                $search_strings = $this->_restricted_strings;
            }

            //loop through each restricted word
            foreach($search_strings as $bad_string){
                //set search pattern for preg_match
                $regexp = "/\b".$bad_string."\b/i";
                //check if a word has been found
                if(preg_match($regexp, $dirty)){
                    //if one has, flip switch and break.
                    $return = false;
                    break;
                }
            }

            return $return;
        }


        //a neat handler to leverage sanitise methods as boolean switches and sanitisers, works essentially like a dictionary.
        public function sanitise_handler($data, $type, $allow_empty = true, $custom_strings = array()){

            //sanitise types available: 'ip', 'int', 'string_int', 'email', 'string', 'float', 'restricted'.
            //check for allow OR empty AND zero
            if($allow_empty || ! empty($data) && ! empty($type)){
                //if empty is allowed reset the type to 'skip' so that validation returns true.
                if(empty($data)){
                    $type = 'skip';
                }

                switch($type){
                    case 'ip':
                        return $this->sanitise_ip($data);
                        break;
                    case 'int':
                        return $this->sanitise_int($data);
                        break;
                    case 'string_int':
                        return $this->sanitise_string_int($data);
                        break;
                    case 'email':
                        return $this->sanitise_email($data);
                        break;
                    case 'url':
                        return $this->sanitise_url($data);
                        break;
                    case 'string':
                        return $this->sanitise_string($data);
                        break;
                    case 'float':
                        return $this->sanitise_float($data);
                        break;
                    case 'restricted':
                        //this method has a special case where an array of custom restricted strings can be parsed
                        if(! empty($custom_strings)){
                            return $this->sanitise_restricted($data, $custom_strings);
                        }else{
                            return $this->sanitise_restricted($data);
                        }
                        break;
                    case 'skip':
                        //sometimes we won't want validation
                        return true;
                        break;
                    default:
                        //if type did not match any available, error out
                        trigger_error('A validation type could not be matched while trying to sanitise data.', E_USER_ERROR);
                        return false;
                        break;
                }
            }else{
                //if there is data missing, error out
                trigger_error('Required parameters where missing or empty while trying to sanitise data.', E_USER_ERROR);
                return false;
            }
        }


        public function sanitise_array($data_array, $type_array, $allow_empty = true){

            //check for allow OR empty AND zero
            if($allow_empty || ! empty($data_array) && ! empty($type_array)){
                //set a container to return sanitised variables in in
                $return_array = array();

                //set a boolean switch to catch validation failures
                $error = false;

                //won't be using the array's keys because this is an abstract function
                $keys = array_keys($data_array);

                //set an increment int
                $i = 0;
                foreach($data_array as $d){
                    //set vars to local loop containing variables.
                    $data = $d;
                    $type = $type_array[$keys[$i]];

                    //rather than put a switch directly inside a for loop, use a handler
                    $sanitise_attempt = $this->sanitise_handler($data, $type, $allow_empty);

                    //check for an EXACTLY false return from the sanitise attempt
                    if($sanitise_attempt === false){
                        //flip the error switch
                        $error = true;
                    }else{
                        //add the sanitised output to the return array
                        $return_array[$keys[$i]] = $sanitise_attempt;
                    }

                    //stop the loop if a validation failure has been detected, essentially the whole data set is invalid.
                    if($error){
                        break;
                    }
                    $i ++;
                }

                //if the error switch has been flipped there was a validation failure.
                if($error){
                    return false;
                }else{
                    return $return_array;
                }
            }else{
                //if there is data missing, error out
                trigger_error('Required parameters where missing or empty while trying to sanitise array.', E_USER_ERROR);
                return false;
            }
        }


        /*
        * Note: this huge string is actually an enormous array of mime types matched to extensions that has been converted to JSON, gzcompressed and then converted to base64. It was the most sensible way to store the array without using a database or separate file.
        *
        * Reference:
        *  $mime_types = array(
        *      'png' => 'image/png',
        *  );
        *
        */

        private $_mime_types = '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';


        //this function will read the mime type of the file given to it and the file's name extension to compare them
        public function check_mime($file, $file_name){ //during upload tmp_name lacks extension so allow two params

            //make sure the file exists, if not, consider it a false match
            if(file_exists($file)){

                //decompress the mim array ready to be referenced
                $compressed_mime_json = $this->_mime_types;
                $de_based_mime_json = $this->decode_base64($compressed_mime_json);
                $de_compressed_mime_json = $this->decompress_string($de_based_mime_json);
                $mime_array = $this->decode_json($de_compressed_mime_json);

                //instantiate file info, magic is from PHP v5.5.4, not using system magic because it changes from server to server.
                $file_info = finfo_open(FILEINFO_MIME, LOCAL.'/secure/library/validate/magic');

                //if file_info could not be loaded, trigger error.
                if($file_info){
                    //get the file's mime type and remove excess text (this relies on a PECL extension)
                    $file_mime_type = finfo_file($file_info, $file);
                    $file_mime_type = explode(';', $file_mime_type);
                    $file_mime_type = $file_mime_type[0];

                    //close the file info instance and connection to file
                    finfo_close($file_info);

                    //isolate file extension from file name so we can compare it to the mime type
                    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

                    //compare the extension against the mime type using our lookup array
                    if($file_mime_type == $mime_array[$file_extension]){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    trigger_error('File Info could not be instantiated, magic could most likely not be loaded.', E_USER_ERROR);
                    return false;
                }

            }else{
                return false;
            }
        }
    }