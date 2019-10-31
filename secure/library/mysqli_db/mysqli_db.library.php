<?php
///////////////////////////////////////////////////////////////////////////////////
// MySQLi Library
// Build: Rare_PHP_Core_Framework
// Purpose: Database Access and Interaction
// Version 1.0.1
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
///////////////////////////////////////////////////////////////////////////////////

class mysqli_db
{

    //define vars
    private static $_instance; //allow self to be called.
    protected $_mysqli; //used as an object container.
    private $_query; //used when manual queries need to be injected.
    private $_where = array(); //used as an array of matching parameters eg: 'field name' => 'value'.
    private $_where_type_list; //used as a container for data type setting against where clauses.
    private $_param_type_list; //used as a container for data type setting against params.
    private $_bind_params = array(''); //used as an array that holds a combination of where condition/table data value types and parameter references, add blank 0 index.

    //rather than calling a connect function, do it immediately when instantiated
    public function __construct($port = null)
    {
        //in most cases we don't need to specify a port.
        if ($port == null) {
            $port = ini_get('mysqli.default_port');
        }

        //connect to database, or die with an error.
        $this->_mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, $port)
        or die('There was a problem connecting to the database');

        //we always use UTF8 in our databases to avoid language and special character problems.
        $this->_mysqli->set_charset('utf8');

        //push connection variable into main instance.
        self::$_instance = $this;
    }


    //method of returning the static instance to allow access to the instantiated object from within another library, inheriting this library would require reloading connection info.
    public static function get_instance()
    {
        return self::$_instance;
    }


    //because we're using persistent variables, we need to reset them after each usage.
    protected function reset()
    {
        $this->_where = array();
        $this->_bind_params = array(''); //set zero index to empty
        unset($this->_query);
        $this->_where_type_list = '';
        $this->_param_type_list = '';
    }


    //pass in a raw query and an array containing the parameters to bind to the prepared statement.
    public function raw_query($query, $bind_params = null)
    {

        //escaping isn't needed, it can mess with complex query strings, be sure to make sure your data is safe
        //$this->_query = $this->escape($query);
        $this->_query = $query;

        $stmt = $this->_prepare_query();

        //if bind params was set to true, collate return data
        if (is_array($bind_params) === true) {
            $params = array(''); //set zero index to empty
            foreach ($bind_params as $prop => $val) {
                $params[0] .= $this->_determine_type($val);
                array_push($params, $bind_params[$prop]);
            }
            call_user_func_array(array($stmt, 'bind_param'), $this->ref_values($params));
        }

        $execute = $stmt->execute();
        $this->reset();

        //check the meta data for a results set
        $meta = $stmt->result_metadata();
        if (!empty($meta)) {
            return $this->_dynamic_bind_results($stmt);
        } else {
            return $execute;
        }
    }


    //controller function to run query from start to finish.
    public function query($query, $num_rows = null)
    {

        //sanitise but leave quotes alone
        $this->_query = filter_var($query, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $stmt = $this->_build_query($num_rows);
        $stmt->execute();
        $this->reset();

        //bind results
        $results = $this->_dynamic_bind_results($stmt);

        //drop array dimension if only one row was requested
        if ($num_rows == 1 && !empty($results)) {
            $results = $results[0];
        }

        return $results;
    }


    //check if a table exists
    public function table_exists($table_name)
    {
        $this->_query = 'SHOW TABLES LIKE "' . $table_name . '"';
        $stmt = $this->_build_query();
        $stmt->execute();
        $this->reset();

        $result = $this->_dynamic_bind_results($stmt);

        //if the result set is empty, then the table doesn't exist
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }


    //a convenient SELECT * function.
    public function get($table_name, $num_rows = null)
    {
        $this->_query = 'SELECT * FROM `' . $table_name . '` ';
        $stmt = $this->_build_query($num_rows);
        $stmt->execute();
        $this->reset();

        //bind results
        $results = $this->_dynamic_bind_results($stmt);

        //drop array dimension if only one row was requested
        if ($num_rows == 1 && !empty($results)) {
            $results = $results[0];
        }

        return $results;
    }


    //a convenient insert function.
    public function insert($table_name, $insert_data)
    {
        $this->_query = 'INSERT INTO `' . $table_name . '` ';
        $stmt = $this->_build_query(null, $insert_data);
        $r = $stmt->execute();
        $this->reset();
        $return = false;

        //julian patch to fix the return true, instead of the ID
        if ($stmt->affected_rows > 0) {
            if ($stmt->insert_id) {
                $return = $stmt->insert_id;
            } else {
                $return = $stmt->affected_rows;
            }
        }
        return $return;
        //TODO GORDON: julian made this patch, was returning TRUE instead of the inserted id
        //return ($stmt->affected_rows > 0 ? $stmt->insert_id || $stmt->affected_rows : false);
    }


    //a convenient update function, make sure to call the 'where' method first.
    public function update($table_name, $table_data)
    {
        $this->_query = 'UPDATE `' . $table_name . '` SET ';

        $stmt = $this->_build_query(null, $table_data);
        $stmt->execute();
        $this->reset();

        return ($stmt->affected_rows > 0);
    }


    //a convenient delete function, make sure to call the 'where' method first.
    public function delete($table_name, $num_rows = null)
    {
        $this->_query = 'DELETE FROM `' . $table_name . '` ';

        $stmt = $this->_build_query($num_rows);
        $stmt->execute();
        $this->reset();

        return ($stmt->affected_rows > 0);
    }


    //this method allows you to specify multiple (method chaining optional) WHERE statements for SQL queries eg: $mysqli_db->where('id', 7)->where('title', 'Title');
    public function where($where_prop, $where_value)
    {
        $this->_where[$where_prop] = $where_value;
        return $this;
    }


    //this method returns the id of the last item inserted
    public function get_insert_id()
    {
        return $this->_mysqli->insert_id;
    }


    //simple function to escape data which might pose a problem or threat (must be called manually on a case by case basis)
    public function escape($str)
    {
        return $this->_mysqli->real_escape_string($str);
    }


    //prepared statements require the data type of the field to be bound with "i" s", etc. This function takes the input, determines what type it is, and then updates the param_type.
    protected function _determine_type($item)
    {
        switch (gettype($item)) {
            case 'NULL':
            case 'string':
                return 's';
                break;
            case 'integer':
                return 'i';
                break;
            case 'boolean':
                return 'i';
                break;

            case 'blob':
                return 'b';
                break;

            case 'double':
                return 'd';
                break;
        }
        return '';
    }


    //abstraction method that compiles the WHERE statement, any passed update data, and the desired rows; then builds the sql query.
    protected function _build_query($num_rows = null, $table_data = null)
    {
        $has_table_data = is_array($table_data);
        $has_conditional = !empty($this->_where);

        //check if the user called the 'where' method.
        if (!empty($this->_where)) {
            //if update data was passed, filter through and create the SQL query.
            if ($has_table_data) {
                $pos = strpos($this->_query, 'UPDATE');

                //PEN-TEST MOD: change condition from not false to absolute zero
                if ($pos === 0) {
                    foreach ($table_data as $prop => $value) {
                        //determines what data type the item is, for binding purposes.
                        $this->_param_type_list .= $this->_determine_type($value);
                        //prepares the reset of the sql query.
                        $this->_query .= ($prop . ' = ?, ');
                    }
                    $this->_query = rtrim($this->_query, ', ');
                }
            }

            //prepare the where portion of the query.
            $this->_query .= ' WHERE ';
            foreach ($this->_where as $column => $value) {
                //determine what data type the where column is, for binding purposes.
                $this->_where_type_list .= $this->_determine_type($value);

                //prepare the reset of the sql query.
                $this->_query .= ($column . ' = ? AND ');
            }
            $this->_query = rtrim($this->_query, ' AND ');
        }

        //determine if is insert query
        if ($has_table_data) {
            $pos = strpos($this->_query, 'INSERT');

            //PEN-TEST MOD: change condition from not false to absolute zero
            if ($pos === 0) {
                //is insert statement
                $keys = array_keys($table_data);
                $values = array_values($table_data);
                $num = count($keys);

                //wrap values in quotes
                foreach ($values as $key => $val) {
                    //if val isn't set or is blank, assume blank rather than error
                    if (!empty($val)) {
                        $values[$key] = "'{$val}'";
                        $this->_param_type_list .= $this->_determine_type($val);
                    } else {
                        $values[$key] = "''";
                        $this->_param_type_list .= $this->_determine_type($val);
                    }
                }

                $this->_query .= '(`' . implode($keys, '`, `') . '`)';
                $this->_query .= ' VALUES(';
                while ($num !== 0) {
                    $this->_query .= '?, ';
                    $num--;
                }
                $this->_query = rtrim($this->_query, ', ');
                $this->_query .= ')';
            }
        }

        //check if the user set a limit
        if (isset($num_rows)) {
            $this->_query .= ' LIMIT ' . (int)$num_rows;
        }

        //prepare query
        $stmt = $this->_prepare_query();

        //prepare table data bind parameters
        if ($has_table_data) {
            $this->_bind_params[0] = $this->_param_type_list;
            foreach ($table_data as $prop => $val) {
                array_push($this->_bind_params, $table_data[$prop]);
            }
        }

        //prepare where condition bind parameters
        if ($has_conditional) {
            if ($this->_where) {
                $this->_bind_params[0] .= $this->_where_type_list;
                foreach ($this->_where as $prop => $val) {
                    array_push($this->_bind_params, $this->_where[$prop]);
                }
            }
        }

        //bind parameters to statement
        if ($has_table_data || $has_conditional) {
            call_user_func_array(array($stmt, 'bind_param'), $this->ref_values($this->_bind_params));
        }

        return $stmt;
    }


    //helper method takes care of 'bind_result method', when the number of variables to pass is unknown.
    protected function _dynamic_bind_results(mysqli_stmt $stmt)
    {
        $parameters = array();
        $results = array();

        $meta = $stmt->result_metadata();

        $row = array();
        while ($field = $meta->fetch_field()) {
            $row[$field->name] = null;
            $parameters[] = &$row[$field->name];
        }

        $stmt->store_result();

        call_user_func_array(array($stmt, 'bind_result'), $parameters);

        while ($stmt->fetch()) {
            $x = array();
            foreach ($row as $key => $val) {
                $x[$key] = $val;
            }
            array_push($results, $x);
        }
        return $results;
    }


    //function to prepare the sql query, throw an error if there is a problem
    protected function _prepare_query()
    {
        $stmt = $this->_mysqli->prepare($this->_query);
        if (!$stmt) {
            trigger_error('A problem was encountered while trying to prepare query: <br />' . $this->_query . '<br />' . $this->_mysqli->error, E_USER_ERROR);
        }
        return $stmt;
    }


    //close connection
    public function __destruct()
    {

        //oracle recommend to use the kill function as well as close
        $thread = $this->_mysqli->thread_id;
        $this->_mysqli->kill($thread);
        $this->_mysqli->close();
    }


    //helper function for php 5.3+. 5.3+ requires array values as reference while 5.2 works with real values.
    protected function ref_values($arr)
    {

        //reference is required for PHP 5.3+
        if (strnatcmp(phpversion(), '5.3') >= 0) {
            $refs = array();
            foreach ($arr as $key => $value) {
                $refs[$key] = &$arr[$key];
            }
            return $refs;
        }
        return $arr;
    }


    /**
     * Check if a column name is part of the table
     *
     * @param $column_name
     * @param $column_type
     * @param $column_value
     * @return boolean
     */
    public function is_column($column_name, $column_type = '', $column_value = '')
    {
        $column_exists = isset($this->columns[$column_name]);
        if (!$column_exists) {
            return false;
        }
        //if needs to check type
        if ($column_type) {
            if ($this->get_column_type($column_name) != $column_type) {
                return false;
            }
        }
        //if needs to check column value against column type
        if ($column_value) {
            if ($this->get_column_type($column_name) != gettype($column_value)) {
                return false;
            }
        }

        return $column_exists;
    }

    /**
     * Returns the column type based on a column name, $this->columns has as key the column name, and the value is the
     * column type
     *
     * @param $column_name
     * @return mixed
     */
    public function get_column_type($column_name) //This gets the column type, cant use _determine_type because will just return the type used in the prepared statement
    {
        $column_type = $this->columns[$column_name];
        return $column_type;
    }

    /**
     * Get the column names and its corresponding types for a table, will return an array in which
     * the key is the column name, the value is the column type
     * @return array
     */
    public function get_columns()
    {
        //build the query
        $sql = 'SELECT * FROM  information_schema.COLUMNS WHERE TABLE_NAME = ? ';
        //build hte parameter of the query
        $params = array(
            get_called_class()
        );
        $raw_columns = $this->raw_query($sql, $params);//must use raw query, if use "get" the table name is surrounded by quotes and fails
        $columns = array();
        foreach ($raw_columns as $raw_column) {
            //translate mysql types to proper ones
            if (preg_match('/^varchar/', $raw_column['COLUMN_TYPE'])) {//can't use  _determine_type because this function returns the character used in the prepared statement
                $raw_column['COLUMN_TYPE'] = 'string';
            }
            if (preg_match('/^int/', $raw_column['COLUMN_TYPE'])) {
                $raw_column['COLUMN_TYPE'] = 'integer';
            }
            if (preg_match('/^tinyint/', $raw_column['COLUMN_TYPE'])) {
                $raw_column['COLUMN_TYPE'] = 'integer';
            }
            $columns[$raw_column['COLUMN_NAME']] = $raw_column['COLUMN_TYPE'];
        }
        return $columns;
    }

    /**
     * Get a row by ID
     *
     * @param $id
     * @return array
     */
    public function get_by_id($id)
    {
        global $mysqli_db, $validate;
        /* @var $mysqli_db mysqli_db */
        /* @var $validate validate */
        $return = array(
            'boolean' => false,
            'response' => 'Unknown error',
            'content' => 'modelid0000000312',
        );
        $column_id = $this->table . '_id';
        if (!$this->is_column($column_id)) {
            $return['response'] = 'Invalid column';
            $return['code'] = 'getbyid00000000000001';
            return $return;
        }
        $column_type = $this->get_column_type($column_id);
        if ($column_type == 'integer') {
            $column_type = 'int';
        }

        $mysqli_db->where($this->table . '_id', $validate->sanitise_handler($id, $column_type));

        $row = $mysqli_db->get($this->table, 1);
        if (!empty($row)) {
            $return['boolean'] = true;
            $return['content'] = $row;
            $return['response'] = 'Data found';
        } else {
            $return['response'] = 'Id not found';
            $return['code'] = 'badid000000000002';
        }
        return $return;
    }

    /**
     * finds a single row by matching all the attributes in the $row_data
     * @param array $row_data
     * @return array
     */
    public function get_by(array $row_data)//get() is a multiple step function if you have a condition, this function is get and where in a single call
    {
        global $validate;
        /* @var $validate validate */
        $return = array(
            'boolean' => false,
            'response' => 'Unknown error',
            'code' => 'getby0000ac00001'
        );
        //build the where part of the query
        foreach ($row_data as $col => $value) {
            $column_type = $this->get_column_type($col);
            if (!$this->is_column($col, $column_type, $value)) {
                $return['response'] = 'Corrupt data';
                $return['code'] = 'getby000x000000002';
                return $return;
            }
            $this->where($col, $validate->sanitise_handler($value, $column_type));//The column type comes from the DB
        }
        $rows = $this->get(get_called_class(), 1);//the table name is the same as the class name that made the function call
        if (!empty($rows)) {
            $return['boolean'] = true;
            $return['content'] = $rows;
            $return['response'] = 'Data found';
        } else {
            $return['response'] = 'No data found';
            $return['code'] = 'getby000x0000000020000';
        }
        return $return;
    }

    /**
     * Find all the rows that matches all the attributes in the $row_data parameter
     *
     * @param array $row_data find all the rows that match these attributes
     * @param int $since EPOCH time to start the search
     * @param string $order column to order by
     * @return array
     */

    public function get_all_by(array $row_data = array(), $since = 0, $order = '', $limit = '')
    {
        $return = array(
            'boolean' => false,
            'response' => 'Unknown error',
            'content' => array(),
            'code' => 'temp00001'
        );
        //build the where part of the query, using concatenation, prepared statements doesn't allow to parametrize ORDER BY also due to the dynamic nature of this function
        $where = array();
        foreach ($row_data as $col => $value) {
            if ($this->is_column($col, $this->get_column_type($col), $value)) {
                $where[] = $col . '=?';//using parameters, concatenation with parameters is also used in $mysqi_db->_build_query, yes, called a 'prepared' statement or query
            } else {
                $return['response'] = 'Corrupt data';
                $return['code'] = 'getallby000x000000002';
                return $return;
            }
        }
        $where_sql = '';
        if (!empty($where)) {
            $where_sql = implode(' AND ', $where) . ' AND ';
        }
        if ($order && $this->is_column($order)) {//Check the order column exists as a table column
            $columns = array_keys($this->columns);//get only the column names
            $index_order = array_search($order, $columns);
            $clean_order = $columns[$index_order];//sanitize the order column
            $order = ' ORDER BY ' . $clean_order;
        }
        //if the since time parameter is valid
        if ($since > 0) {
            $since = intval($since);//convert to integer
        }
        if (!empty($limit) && $limit >0) {
            $limit = ' LIMIT '.((int)$limit);
        }
        $row_data['insert_time'] = $since;
        $row_data['update_time'] = $since;

        $sql = 'SELECT * FROM  ' . $this->table . ' WHERE  ' . $where_sql . '  (insert_time >  ?  OR update_time>  ? ) ' . $order. ' '.$limit; //using concatenation, can't use a parameter in the ORDER BY
        $rows = $this->raw_query($sql, $row_data);
        if (count($rows) > 0) {
            $return['boolean'] = true;
            $return['content'] = $rows;
            $return['response'] = 'Data found';
        }
        return $return;
    }
}
