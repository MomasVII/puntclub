<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // CSV Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: Read and write CSV data
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class csv{


        private $_fields = array(); //override field names.
        private $_auto_depth = 15; //number of rows to analyze when attempting to auto-detect delimiter.
        private $_auto_non_chars = "a-zA-Z0-9\n\r"; //characters to ignore when attempting to auto-detect delimiters.
        private $_auto_preferred = ",;\t.:|"; //default delimiter characters (used when no delimiters could be detected.)
        private $_convert_encoding = false; //boolean used to turn encoding conversion on and off (rarely used.)
        private $_input_encoding = 'ISO-8859-1'; //default incoming character encoding (rarely used.)
        private $_output_encoding = 'ISO-8859-1'; //default outgoing character encoding (rarely used.)
        private $_linefeed = "\r\n"; //line return character - used by unparse, save, and output functions.
        private $_output_delimiter = ','; //default output delimiter
        private $_output_filename = 'data.csv'; //default output filename
        public $heading = true; //use first line/entry as field names.
        public $sort_by = null; //sort entries by this field.
        public $sort_reverse = false; //reverse the sort order.
        public $delimiter = ','; //delimiter (default: UTF-8/ISO comma.)
        public $enclosure = '"'; //enclosure (default: UTF-8/ISO double quote.)
        public $conditions = null; //basic SQL style conditions for row matching.
        public $offset = null; //number of rows to ignore from beginning of data.
        public $limit = null; //returned row number limit.
        public $file; //current working file
        public $file_data; //working file data
        public $titles = array(); //array of field values
        public $data = array(); //two dimensional array of CSV data

        //parse CSV file or string
        public function parse($input = null, $offset = null, $limit = null, $conditions = null){

            //check that input isn't empty
            if(! empty($input)){
                //set any params that have been specified
                if($offset !== null){
                    $this->offset = $offset;
                }

                if($limit !== null){
                    $this->limit = $limit;
                }

                if(count($conditions) > 0){
                    $this->conditions = $conditions;
                }

                //check if the input is a file and is readable, else treat it as a string
                if(is_readable($input)){
                    $this->data = $this->parse_file($input);
                }else{
                    $this->file_data = & $input;
                    $this->data = $this->parse_string();
                }

                //if data wasn't able to be set, return false
                if($this->data === false){
                    return false;
                }
            }

            return true;
        }


        //save changes or save new file
        public function save($file = null, $data = array(), $append = false, $fields = array()){

            if($file === null){
                $file = & $this->file;
            }

            $mode = ($append) ? 'at' : 'wt';

            $is_php = (preg_match('/\.php$/i', $file)) ? true : false;

            return $this->wfile($file, $this->unparse($data, $fields, $append, $is_php), $mode);
        }


        //generate CSV string for output
        public function output($output = true, $filename = null, $data = array(), $fields = array(), $delimiter = null){

            if(empty($filename)){
                $filename = $this->_output_filename;
            }
            if($delimiter === null){
                $delimiter = $this->_output_delimiter;
            }
            $data = $this->unparse($data, $fields, null, null, $delimiter);

            //if the output parameter is set, header the file to the browser
            if($output){
                header('Content-type: application/csv');
                header('Content-Disposition: inline; filename="'.$filename.'"');
                echo $data;
                die();
            }

            return $data;
        }


        //convert character encoding
        public function encoding($input = null, $output = null){

            $this->_convert_encoding = true;
            if($input !== null){
                $this->_input_encoding = $input;
            }
            if($output !== null){
                $this->_output_encoding = $output;
            }
        }


        //attempt to detect delimiter by running set number of rows
        public function auto($file = null, $parse = true, $search_depth = null, $preferred = null, $enclosure = null){

            if($file === null){
                $file = $this->file;
            }
            if(empty($search_depth)){
                $search_depth = $this->_auto_depth;
            }
            if($enclosure === null){
                $enclosure = $this->enclosure;
            }

            if($preferred === null){
                $preferred = $this->_auto_preferred;
            }

            if(empty($this->file_data)){
                if($this->check_data($file)){
                    $data = & $this->file_data;
                }else{
                    return false;
                }
            }else{
                $data = & $this->file_data;
            }

            $chars = array();
            $strlen = strlen($data);
            $enclosed = false;
            $n = 1;
            $to_end = true;

            //walk specific depth finding possible delimiter characters
            for($i = 0; $i < $strlen; $i ++){
                $ch = $data{$i};
                $nch = (isset($data{$i + 1})) ? $data{$i + 1} : false;
                $pch = (isset($data{$i - 1})) ? $data{$i - 1} : false;

                //open and closing quotes
                if($ch == $enclosure && (! $enclosed || $nch != $enclosure)){
                    $enclosed = ($enclosed) ? false : true;

                    //inline quotes
                }elseif($ch == $enclosure && $enclosed){
                    $i ++;

                    //end of row
                }elseif(($ch == "\n" && $pch != "\r" || $ch == "\r") && ! $enclosed){
                    if($n >= $search_depth){
                        $strlen = 0;
                        $to_end = false;
                    }else{
                        $n ++;
                    }
                    //count character
                }elseif(! $enclosed){
                    if(! preg_match('/['.preg_quote($this->_auto_non_chars, '/').']/i', $ch)){
                        if(! isset($chars[$ch][$n])){
                            $chars[$ch][$n] = 1;
                        }else{
                            $chars[$ch][$n] ++;
                        }
                    }
                }
            }

            //filtering
            $depth = ($to_end) ? $n - 1 : $n;
            $filtered = array();
            foreach($chars as $char => $value){
                $match = $this->check_count($char, $value, $depth, $preferred);
                if($match){
                    $filtered[$match] = $char;
                }
            }

            //capture most probable delimiter
            ksort($filtered);
            $delimiter = reset($filtered);
            $this->delimiter = $delimiter;

            //parse data
            if($parse){
                $this->data = $this->parse_string();
            }

            return $delimiter;

        }


        //read file to string and call parse_string()
        public function parse_file($file = null){

            if($file === null){
                $file = $this->file;
            }
            if(empty($this->file_data)){
                $this->load_data($file);
            }
            return (! empty($this->file_data)) ? $this->parse_string() : false;
        }


        //parse CSV strings to arrays
        public function parse_string($data = null){

            if(empty($data)){
                if($this->check_data()){
                    $data = & $this->file_data;
                }else{
                    return false;
                }
            }

            $rows = array();
            $row = array();
            $row_count = 0;
            $current = '';
            $head = (! empty($this->_fields)) ? $this->_fields : array();
            $col = 0;
            $enclosed = false;
            $was_enclosed = false;
            $strlen = strlen($data);

            //walk through each character
            for($i = 0; $i < $strlen; $i ++){
                $ch = $data{$i};
                $nch = (isset($data{$i + 1})) ? $data{$i + 1} : false;
                $pch = (isset($data{$i - 1})) ? $data{$i - 1} : false;

                //open and closing quotes
                if($ch == $this->enclosure && (! $enclosed || $nch != $this->enclosure)){
                    $enclosed = ($enclosed) ? false : true;
                    if($enclosed){
                        $was_enclosed = true;
                    }

                    //inline quotes
                }elseif($ch == $this->enclosure && $enclosed){
                    $current .= $ch;
                    $i ++;

                    //end of field/row
                }elseif(($ch == $this->delimiter || ($ch == "\n" && $pch != "\r") || $ch == "\r") && ! $enclosed){
                    if(! $was_enclosed){
                        $current = trim($current);
                    }
                    $key = (! empty($head[$col])) ? $head[$col] : $col;
                    $row[$key] = $current;
                    $current = '';
                    $col ++;

                    //end of row
                    if($ch == "\n" || $ch == "\r"){
                        if($this->validate_offset($row_count) && $this->validate_row_conditions($row, $this->conditions)){
                            if($this->heading && empty($head)){
                                $head = $row;
                            }elseif(empty($this->_fields) || (! empty($this->_fields) && (($this->heading && $row_count > 0) || ! $this->heading))){
                                if(! empty($this->sort_by) && ! empty($row[$this->sort_by])){
                                    if(isset($rows[$row[$this->sort_by]])){
                                        $rows[$row[$this->sort_by].'_0'] = & $rows[$row[$this->sort_by]];
                                        unset($rows[$row[$this->sort_by]]);
                                        for($sn = 1; isset($rows[$row[$this->sort_by].'_'.$sn]); $sn ++){
                                        }
                                        $rows[$row[$this->sort_by].'_'.$sn] = $row;
                                    }else{
                                        $rows[$row[$this->sort_by]] = $row;
                                    }
                                }else{
                                    $rows[] = $row;
                                }
                            }
                        }
                        $row = array();
                        $col = 0;
                        $row_count ++;
                        if($this->sort_by === null && $this->limit !== null && count($rows) == $this->limit){
                            $i = $strlen;
                        }
                    }

                    //append character to current field
                }else{
                    $current .= $ch;
                }
            }
            $this->titles = $head;
            if(! empty($this->sort_by)){
                ($this->sort_reverse) ? krsort($rows) : ksort($rows);
                if($this->offset !== null || $this->limit !== null){
                    $rows = array_slice($rows, ($this->offset === null ? 0 : $this->offset), $this->limit, true);
                }
            }
            return $rows;
        }


        //create CSV data from array
        private function unparse($data = array(), $fields = array(), $append = false, $is_php = false, $delimiter = null){

            if(! is_array($data) || empty($data)){
                $data = & $this->data;
            }
            if(! is_array($fields) || empty($fields)){
                $fields = & $this->titles;
            }
            if($delimiter === null){
                $delimiter = $this->delimiter;
            }

            $string = ($is_php) ? "<?php header('Status: 403'); die(' '); ?>".$this->_linefeed : '';
            $entry = array();

            //create heading
            if($this->heading && ! $append){
                foreach($fields as $value){
                    $entry[] = $this->enclose_value($value);
                }
                $string .= implode($delimiter, $entry).$this->_linefeed;
            }

            //reset the entry container var to be safe
            $entry = array();

            //alter the loop depth if appending
            if($append){
                $entry = array();
                foreach($data as $value){
                    $entry[] = $this->enclose_value($value);
                }
                $string .= implode($delimiter, $entry).$this->_linefeed;
            }else{
                foreach($data as $row){
                    foreach($row as $value){
                        $entry[] = $this->enclose_value($value);
                    }
                    $string .= implode($delimiter, $entry).$this->_linefeed;
                    $entry = array();
                }
            }


            return $string;
        }


        //load local file or string
        private function load_data($input = null){

            $data = null;
            $file = null;
            if($input === null){
                $file = $this->file;
            }elseif(file_exists($input)){
                $file = $input;
            }else{
                $data = $input;
            }
            if(! empty($data) || $data = $this->rfile($file)){
                if($this->file != $file){
                    $this->file = $file;
                }
                if(preg_match('/\.php$/i', $file) && preg_match('/<\?.*?\?>(.*)/ims', $data, $strip)){
                    $data = ltrim($strip[1]);
                }
                if($this->_convert_encoding){
                    $data = iconv($this->_input_encoding, $this->_output_encoding, $data);
                }
                if(substr($data, - 1) != "\n"){
                    $data .= "\n";
                }
                $this->file_data = & $data;
                return true;
            }
            return false;
        }


        //validate a row against specified conditions
        private function validate_row_conditions($row = array(), $conditions = null){

            if(! empty($row)){
                if(! empty($conditions)){
                    $conditions = (strpos($conditions, ' OR ') !== false) ? explode(' OR ', $conditions) : array($conditions);
                    $or = '';
                    foreach($conditions as $value){
                        if(strpos($value, ' AND ') !== false){
                            $value = explode(' AND ', $value);
                            $and = '';
                            foreach($value as $v){
                                $and .= $this->validate_row_condition($row, $v);
                            }
                            $or .= (strpos($and, '0') !== false) ? '0' : '1';
                        }else{
                            $or .= $this->validate_row_condition($row, $value);
                        }
                    }
                    return (strpos($or, '1') !== false) ? true : false;
                }
                return true;
            }
            return false;
        }


        //validate a row against a single condition
        private function validate_row_condition($row, $condition){

            $operators = array(
                '=', 'equals', 'is', '!=', 'is not', '<', 'is less than', '>', 'is greater than', '<=',
                'is less than or equals', '>=', 'is greater than or equals', 'contains', 'does not contain',
            );
            $operators_regex = array();
            foreach($operators as $value){
                $operators_regex[] = preg_quote($value, '/');
            }
            $operators_regex = implode('|', $operators_regex);
            if(preg_match('/^(.+) ('.$operators_regex.') (.+)$/i', trim($condition), $capture)){
                $field = $capture[1];
                $op = $capture[2];
                $value = $capture[3];
                if(preg_match('/^([\'\"]{1})(.*)([\'\"]{1})$/i', $value, $capture)){
                    if($capture[1] == $capture[3]){
                        $value = $capture[2];
                        $value = str_replace("\\n", "\n", $value);
                        $value = str_replace("\\r", "\r", $value);
                        $value = str_replace("\\t", "\t", $value);
                        $value = stripslashes($value);
                    }
                }
                if(array_key_exists($field, $row)){
                    if(($op == '=' || $op == 'equals' || $op == 'is') && $row[$field] == $value){
                        return '1';
                    }elseif(($op == '!=' || $op == 'is not') && $row[$field] != $value){
                        return '1';
                    }elseif(($op == '<' || $op == 'is less than') && $row[$field] < $value){
                        return '1';
                    }elseif(($op == '>' || $op == 'is greater than') && $row[$field] > $value){
                        return '1';
                    }elseif(($op == '<=' || $op == 'is less than or equals') && $row[$field] <= $value){
                        return '1';
                    }elseif(($op == '>=' || $op == 'is greater than or equals') && $row[$field] >= $value){
                        return '1';
                    }elseif($op == 'contains' && preg_match('/'.preg_quote($value, '/').'/i', $row[$field])){
                        return '1';
                    }elseif($op == 'does not contain' && ! preg_match('/'.preg_quote($value, '/').'/i', $row[$field])){
                        return '1';
                    }else{
                        return '0';
                    }
                }
            }
            return '1';
        }


        //validates if the row is within the offset or not if sorting is disabled
        private function validate_offset($current_row){

            if($this->sort_by === null && $this->offset !== null && $current_row < $this->offset){
                return false;
            }
            return true;
        }


        //enclose values if needed
        private function enclose_value($value = null){

            if($value !== null && $value != ''){
                $delimiter = preg_quote($this->delimiter, '/');
                $enclosure = preg_quote($this->enclosure, '/');
                if(preg_match("/".$delimiter."|".$enclosure."|\n|\r/i", $value) || ($value{0} == ' ' || substr($value, - 1) == ' ')){
                    $value = str_replace($this->enclosure, $this->enclosure.$this->enclosure, $value);
                    $value = $this->enclosure.$value.$this->enclosure;
                }
            }
            return $value;
        }


        //check file data
        private function check_data($file = null){

            if(empty($this->file_data)){
                if($file === null){
                    $file = $this->file;
                }
                return $this->load_data($file);
            }
            return true;
        }

        //check if passed info might be delimiter
        private function check_count($char, $array, $depth, $preferred){

            if($depth == count($array)){
                $first = null;
                $equal = null;
                $almost = false;
                foreach($array as $value){
                    if($first == null){
                        $first = $value;
                    }elseif($value == $first && $equal !== false){
                        $equal = true;
                    }elseif($value == $first + 1 && $equal !== false){
                        $equal = true;
                        $almost = true;
                    }else{
                        $equal = false;
                    }
                }
                if($equal){
                    $match = ($almost) ? 2 : 1;
                    $pref = strpos($preferred, $char);
                    $pref = ($pref !== false) ? str_pad($pref, 3, '0', STR_PAD_LEFT) : '999';
                    return $pref.$match.'.'.(99999 - str_pad($first, 5, '0', STR_PAD_LEFT));
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        //read local file
        private function rfile($file = null){

            if(is_readable($file)){
                if(! ($fh = fopen($file, 'r'))){
                    return false;
                }
                $data = fread($fh, filesize($file));
                fclose($fh);
                return $data;
            }
            return false;
        }

        //write local file
        private function wfile($file, $string = '', $mode = 'wb', $lock = 2){

            $fp = fopen($file, $mode);
            if($fp){
                flock($fp, $lock);
                $re = fwrite($fp, $string);
                $re2 = fclose($fp);
                if($re != false && $re2 != false){
                    return true;
                }
            }
            return false;
        }
    }