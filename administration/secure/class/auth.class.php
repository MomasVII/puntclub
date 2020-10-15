<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // Login Authentication Class
    // Site: postcode.auspost.com.au
    // Purpose: This is a unique login class specifically for the Rare Core
    // Version 0.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class auth
    {

        //checks to run on every authenticated endpoint
        public function __construct()
        {

            //if the user want's to sign out or isn't authenticated and we aren't on the login page
            if ((isset($_GET['sign_out']) || !$this->is_signed_in()) && $_SERVER['PHP_SELF'] !== '/administration/index.php') {

                //destroy the session
                session_destroy();

                //send back to login
                header('Location: '.LOCAL.'index.html');
                die();
            }
        }


        //generate CSRF token
        //PEN-TEST MOD: add CSRF token generator based on OWASP's example (https://www.owasp.org/index.php/PHP_CSRF_Guard)
        public function generate_csrf_token($form_name)
        {
            //FIXME: replace SHA512/rand method with more powerful entropy generator
            //create token using sha512
            $token = hash('sha512', mt_rand(0, mt_getrandmax()));

            //set token in session
            $_SESSION['token'][$form_name] = $token;

            return $token;
        }

        //validate CSRF token
        //PEN-TEST MOD: add CSRF token validator based on OWASP's example
        private function validate_csrf_token($form_name, $request_token)
        {

            //check that the token we are trying to validate against exists in the session
            if (isset($_SESSION['token'][$form_name]) && !empty($_SESSION['token'][$form_name])) {

                //save the session token then unset it
                $session_token = $_SESSION['token'][$form_name];
                unset($_SESSION['token'][$form_name]);

                //if the session token matches the request token; it's valid
                if ($session_token === $request_token) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        //check if end-user is signed in
        public function is_signed_in()
        {
            if (isset($_SESSION['signed_in']) && $_SESSION['signed_in']) {
                return true;
            } else {
                return false;
            }
        }

        //hash a password to store in the DB
        public function hash_password($password)
        {
            global $validate, $password_hash;

            $return = array(
                'boolean' => false,
                'content' => '',
            );

            if ($validate->sanitise_handler($password, 'string', false)) {
                $return['content'] = $password_hash->hash_password($password);
                $return['boolean'] = true;
            }

            return $return;
        }

        //user sign in
        public function sign_in($post)
        {
            global $mysqli_db, $validate, $password_hash;

            $return = array(
                'boolean' => false,
                'response' => '',
            );

            //match and verify CSRF token
            //PEN-TEST MOD: verify CSRF token based on OWASPS functions but slightly different method
            $form_name = $post['csrf_name'];
            unset($post['csrf_name']);
            $request_token = $post['csrf_token'];
            unset($post['csrf_token']);
            $csrf_check = $this->validate_csrf_token($form_name, $request_token);

            //make sure the referrer is correct
            if ($validate->check_referer() && $csrf_check) {

                //destroy old session and begin new
                session_regenerate_id(true);

                //methodically define and validate data
                $type_array = array();
                $data_array = array();

                //set metric array
                $type_array['last_sign_in'] = 'int';
                //$type_array['last_ip'] = 'ip';
                $type_array['username'] = 'string';
                $type_array['password'] = 'string';

                //set data array
                $data_array['last_sign_in'] = time();
                //$data_array['last_ip'] = END_USER_IP; //removed because POST use something weird that doesn't deliver IP and this was locking POST staff out of admin
                $data_array['username'] = $post['username'];
                $data_array['password'] = $post['password'];

                //sanatise
                $sanatise = $validate->sanitise_array($data_array, $type_array, false); //last param is whether or not to allow empty/zero to pass validation.
                if ($sanatise) {

                    //search for matching user that isn't disabled
                    $mysqli_db->where('disabled', 0);
                    $mysqli_db->where('username', $data_array['username']);
                    $user_select = $mysqli_db->get('user', 1);

                    if (!empty($user_select)) {

                        //use password_hash lib to check submitted password against stored hash
                        $password_check = $password_hash->check_password(SALT.$data_array['password'], $user_select['password']);
                        if ($password_check) {

                            //user was found and password was correct, update user record with ip and time
                            $update_data_array = array();
                            $update_data_array['last_sign_in'] = $data_array['last_sign_in'];
                            //$update_data_array['last_ip'] = $data_array['last_ip'];

                            $mysqli_db->where('user_id', $user_select['user_id']);
                            $update_user = $mysqli_db->update('user', $update_data_array);

                            if ($update_user) {
                                //set login auth to true
                                $_SESSION['signed_in'] = true;

                                //set general details to session
                                $_SESSION['user_id'] = $user_select['user_id'];
                                $_SESSION['email'] = $user_select['email'];
                                $_SESSION['username'] = $user_select['username'];
                                $_SESSION['first_name'] = $user_select['first_name'];
                                $_SESSION['last_name'] = $user_select['last_name'];
                                //$_SESSION['last_ip'] = $update_data_array['last_ip'];
                                $_SESSION['last_sign_in'] = $update_data_array['last_sign_in'];
                                $_SESSION['access'] = $user_select['access'];

                                $return['boolean'] = true;
                            } else {
                                $return['response'] = 'An error occurred while trying to log you in, please try again';
                            }
                        } else {
                            $return['response'] = 'Your password was incorrect, please try again';
                        }
                    } else {
                        $return['response'] = 'The username you entered does not exist, please try again';
                    }
                } else {
                    $return['response'] = 'Invalid data was detected, you have not been logged in';
                }
            } else {
                $return['response'] = 'A malicious sign in attempt was detected, your details have been recorded';
            }

            return $return;
        }
    }
