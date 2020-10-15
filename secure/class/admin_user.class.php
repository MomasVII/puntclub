<?php
///////////////////////////////////////////////////////////////////////////////////
// Administration User Class
// Site: postcode.auspost.com.au
// Purpose: Manage Users
// Version 0.0.1
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
///////////////////////////////////////////////////////////////////////////////////

class admin_user
{

    //get user's data by id
    public function get_by_id($id)
    {
        global $mysqli_db, $validate;

        $return = array();

        if(empty($id) || !$validate->sanitise_handler($id, 'int', false)) {
            return $return;
        }

        $mysqli_db->where('user_id', $id);
        $user_select = $mysqli_db->get('user', 1);
        if (!empty($user_select)) {
            $return = $user_select;
        }

        return $return;
    }


    //get user's first name and last name by id
    public function get_user_name_by_id($id)
    {
        global $mysqli_db, $validate;

        $return = 'Unknown';

        if(empty($id) || !$validate->sanitise_handler($id, 'int', false)) {
            return $return;
        }

        $mysqli_db->where('user_id', $id);
        $user_select = $mysqli_db->get('user', 1);
        if (!empty($user_select)) {
            $return = $user_select['first_name'] . ' ' . $user_select['last_name'];
        }

        return $return;
    }


    //get user's email address by id
    public function get_email_by_id($id)
    {
        global $mysqli_db, $validate;

        $return = 'Unknown';

        if(empty($id) || !$validate->sanitise_handler($id, 'int', false)) {
            return $return;
        }

        $mysqli_db->where('user_id', $id);
        $user_select = $mysqli_db->get('user', 1);
        if (!empty($user_select)) {
            $return = $user_select['email'];
        }

        return $return;
    }


    //select all users
    public function get_all()
    {
        global $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => '',
            'content' => '',
        );

        $user_select = $mysqli_db->query('SELECT *, IF(disabled,3,IF(access = \'admin\',1, 2)) reorder FROM `user` ORDER BY reorder ASC, insert_time DESC, `first_name` ASC, `last_name` ASC');

        if (!empty($user_select)) {
            $return['boolean'] = true;
            $return['content'] = $user_select;
        } else {
            $return['response'] = 'No active users were found';
        }

        return $return;
    }


    //reset and email user a new password
    public function reset_password($id)
    {
        global $mysqli_db, $validate, $auth, $shortcut;

        $return = array(
            'boolean' => false,
            'response' => 'Your password couldn\'t be reset',
        );

        if(empty($id) || !$validate->sanitise_handler($id, 'int', false)) {
            return $return;
        }

        //get the old data of the user
        $user_data = $this->get_by_id($id);
        if (!$user_data) {
            return $return;
        }

        //set the email template by checking if is a new user or is is a password reset based on the existence of the password
        $email_template_filename = 'user_new_password.html';
        $email_title = 'Punt Club Title';
        if (!empty($user_data['password'])) {
            $email_template_filename = 'user_reset_password.html';
            $email_title = 'Punt Club Title';
        }

        //generate a new password
        $password = $shortcut->random_string(8);
        $password_hash = $auth->hash_password(SALT . $password)['content'];

        //set user password update data
        $update_data = array();
        $update_data['password'] = $password_hash;

        //set validation types
        $type_array = array();
        $type_array['password'] = 'string';

        //validate data
        $check = $validate->sanitise_array($update_data, $type_array, true); //validate and allow blank/zero
        if(!$check){
            return $return;
        }

        //run the update request
        $mysqli_db->where('user_id', $id);
        $update = $mysqli_db->update('user', $update_data);
        if(!$update){
            return $return;
        }

        //build html email
        $name = $validate->camel_case($user_data['first_name']);
        $username = $user_data['username'];
        $message = file_get_contents(LOCAL . 'secure/email/' . $email_template_filename);

        //write parameters into email template
        $message = str_replace('{{name}}', $name, $message);
        $message = str_replace('{{username}}', $username, $message);
        $message = str_replace('{{password}}', $password, $message);

        //build confirmation email and send via SES
        require_once(ROOT . 'secure/class/SES.php');
        $m = new SimpleEmailServiceMessage();
        $m->addTo($user_data['email']);
        $m->setFrom('Punt CLub <no-reply@puntclub.com.au>');
        $m->setSubject($email_title);
        $m->setMessageFromString('', $message);
        $ses = new SimpleEmailService(IAM_KEY_ID, IAM_KEY_SECRET);
        try {
            $send = $ses->sendEmail($m);
            if (!$send) {

                $return['response'] = 'Your password has been reset but couldn\'t be sent to the user, the user\'s password is: '.$password;
                return $return;
            }
        } catch (Exception $e) {
            return $return;
        }

        $return['boolean'] = true;
        $return['response'] = 'Your password has been reset, an email has been sent advising the user';
        return $return;
    }


    //create a user
    function insert()
    {
        global $validate, $mysqli_db, $auth;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to save your user, please try again!',
        );
        //make sure the referrer is correct
        if (!$validate->check_referer()) {
            return $return;
        }

        //make sure post fields are set
        if (
            !isset($_POST['first_name']) ||
            !isset($_POST['last_name']) ||
            !isset($_POST['username']) ||
            !isset($_POST['email']) ||
            !isset($_POST['access']) ||
            empty($_POST['first_name']) ||
            empty($_POST['last_name']) ||
            empty($_POST['username']) ||
            empty($_POST['email']) ||
            empty($_POST['access'])
        ){
            $return['response'] = 'Please make sure you\'ve completed the required fields';
            return $return;
        }

        //set insert data
        $insert_data = array();
        $insert_data['insert_time'] = time();
        $insert_data['first_name'] = $_POST['first_name'];
        $insert_data['last_name'] = $_POST['last_name'];
        $insert_data['username'] = $_POST['username'];
        $insert_data['email'] = $_POST['email'];
        $insert_data['access'] = $_POST['access'];
        $insert_data['password'] = $auth->hash_password(SALT . $_POST['password'])['content'];

        //set validation types
        $type_array = array();
        $type_array['insert_time'] = 'int';
        $type_array['first_name'] = 'string';
        $type_array['last_name'] = 'string';
        $type_array['username'] = 'string';
        $type_array['email'] = 'email';
        $type_array['access'] = 'string';
        $type_array['password'] = 'string';

        //validate data
        $check = $validate->sanitise_array($insert_data, $type_array, true); //validate and allow blank/zero
        if(!$check){
            $return['response'] = 'Please make sure you\'ve entered valid details into the required fields';
            return $return;
        }

        //run the insert request
        $insert = $mysqli_db->insert('user', $insert_data);
        var_dump($insert_data);
        if(!$insert){
            return $return;
        }

        //set a password for the user
        /*$set_password = $this->reset_password($insert);
        if(!$set_password['boolean']){
            $return['response'] = 'Your user has been saved but a password couldn\'t be sent to them';
            return $return;
        }*/

        $return['boolean'] = true;
        $return['response'] = 'Your user has been saved, they will receive an email containing their access credentials';
        return $return;
    }

    //update a user
    function update($id)
    {
        global $validate, $mysqli_db;

        $return = array(
            'boolean' => false,
            'response' => 'An error occurred while trying to save your user, please try again.',
        );

        if(empty($id) || !$validate->sanitise_handler($id, 'int', false)) {
            return $return;
        }

        //make sure the referrer is correct
        if (!$validate->check_referer()) {
            return $return;
        }

        //make sure post fields are set
        if (
            !isset($_POST['first_name']) ||
            !isset($_POST['last_name']) ||
            !isset($_POST['username']) ||
            !isset($_POST['email']) ||
            !isset($_POST['access']) ||
            empty($_POST['first_name']) ||
            empty($_POST['last_name']) ||
            empty($_POST['username']) ||
            empty($_POST['email']) ||
            empty($_POST['access'])
        ){
            $return['response'] = 'Please make sure you\'ve completed the required fields';
            return $return;
        }

        //set update data
        $update_data = array();
        $update_data['first_name'] = $_POST['first_name'];
        $update_data['last_name'] = $_POST['last_name'];
        $update_data['username'] = $_POST['username'];
        $update_data['email'] = $_POST['email'];
        $update_data['access'] = $_POST['access'];
        if($_POST['access'] === 'disabled'){
            $update_data['access'] = 'manager';
        }

        //set validation types
        $type_array = array();
        $type_array['first_name'] = 'string';
        $type_array['last_name'] = 'string';
        $type_array['username'] = 'string';
        $type_array['email'] = 'email';
        $type_array['access'] = 'string';

        //validate data
        $check = $validate->sanitise_array($update_data, $type_array, true); //validate and allow blank/zero
        if(!$check){
            $return['response'] = 'Please make sure you\'ve entered valid details into the required fields';
            return $return;
        }

        //run the update request
        $mysqli_db->where('user_id', $id);
        $update = $mysqli_db->update('user', $update_data);
        if(!$update){
            return $return;
        }

        $return['boolean'] = true;
        $return['response'] = 'Your user has been saved';
        return $return;
    }
}
