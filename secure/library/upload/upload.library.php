<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // Upload Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: File upload management
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class upload{

        private $_file = array(); //file data.
        private $_file_post = array(); //file post array.
        private $_destination; //destination directory (usually 'dyn_in')
        private $_file_info; //file_info instance.
        private $_max_file_size; //max file size definition.
        private $_mime_types = array(); //allowed mime types.
        private $_tmp_name; //temporary file path container.
        private $_validation_errors = array(); //file validation errors.
        private $_filename; //new file name.
        private $_callbacks = array(); //callbacks (file size check, mime, etc)

        //define root constant and set/create destination path.
        public function __construct($destination = null){

            //check if the destination is set, set and create destination path, or trigger error on failure.
            if($destination != null){
                if(! $this->set_destination($destination)){
                    trigger_error('The upload destination ('.$this->_destination.') could not be set.', E_USER_ERROR);
                    return false;
                }
            }


            //create finfo object or trigger error on failure.
            $this->_file_info = new finfo();
            if(! $this->_file_info){
                trigger_error('File_info extension could not be instantiated.', E_USER_ERROR);
                return false;
            }else{
                return true;
            }
        }


        //check and save uploaded file, returns data about current upload.
        public function upload($retain_filename = false){

            //perform file check before saving
            if($this->check()){
                //save the file
                $this->save_file($retain_filename);
            }

            //return state data
            return $this->get_state();
        }


        //validate file, execute callbacks and return any errors that might occur, returns true on success
        public function check(){

            //execute callbacks (check file size, mime, also external callbacks
            $this->validate();

            //add error messages
            $this->_file['errors'] = $this->get_errors();

            //change file validation status
            $this->_file['status'] = empty($this->_validation_errors);

            return $this->_file['status'];
        }


        //get the current state of the file upload as an array
        public function get_state(){

            return $this->_file;
        }


        //get validation errors
        public function get_errors(){

            return $this->_validation_errors;
        }


        //set allowed mime types
        public function set_allowed_mime_types($mimes){

            $this->_mime_types = $mimes;

            //if mime types is set -> set callback
            $this->_callbacks[] = 'check_mime_type';
        }


        //set maximum file size
        public function set_max_file_size($size){

            $this->_max_file_size = $size;

            //if max file size is set -> set callback
            $this->_callbacks[] = 'check_file_size';
        }


        //set file array to object
        public function file($file){
            $this->set_file_array($file);
        }


        //set destination path
        public function set_destination($destination){

            $this->_destination = $destination.'/';

            if($this->destination_exist()){
                return true;
            }else{
                return false;
            }
        }


        //save file to server
        private function save_file($retain_filename){

            //create and set new filename
            $this->create_new_filename();

            //check if we need to generate a new file name or use the same as uploaded
            if($retain_filename){
                //do a quick file name sanitise
                $clean_file_name = preg_replace(
                    array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'),
                    array('_', '.', ''),
                    $this->_file_post['name']
                );
                //set filename to state data
                $this->_file['filename'] = $clean_file_name;
            }else{
                //set filename to state data
                $this->_file['filename'] = $this->_filename;
            }

            //set full path to state data
            $this->_file['full_path'] = $this->_destination.$this->_file['filename'];

            //move file from temporary location to final destination
            $status = move_uploaded_file($this->_tmp_name, $this->_file['full_path']);

            //set the state data status to true
            $this->_file['status'] = $status;

            //return upload state data
            return $this->get_state();
        }


        //set data about file
        private function set_file_data(){

            //get the size of the file
            $file_size = $this->get_file_size();

            //set data array
            $this->_file = array(
                'status' => false, 'destination' => $this->_destination, 'size_in_bytes' => $file_size,
                'size_in_mb' => $this->bytes_to_mb($file_size), 'mime' => $this->get_file_mime(),
                'original_filename' => $this->_file_post['name'], 'tmp_name' => $this->_file_post['tmp_name'],
                'post_data' => $this->_file_post,
            );
        }


        //execute validation callbacks
        private function validate(){

            //get current errors
            $errors = $this->get_errors();

            //check if errors already exist
            if(empty($errors)){
                //set data about current file
                $this->set_file_data();

                //execute internal callbacks
                $this->execute_callbacks($this->_callbacks, $this);
            }
        }


        //callback handler
        private function execute_callbacks($callbacks, $object){

            if(! empty($callbacks) && ! empty($object)){

                foreach($callbacks as $method){
                    $object->$method($this);
                }
            }
        }


        //callback function to check mime types
        protected function check_mime_type($object){

            if(! empty($object->_mime_types)){
                if(! in_array($object->_file['mime'], $object->_mime_types)){
                    $this->_validation_errors[] = 'Your file could not be uploaded, the file type is not allowed.';
                }
            }
        }


        //file size validation callback
        protected function check_file_size($object){

            //check that a file size limit is set
            if(! empty($object->_max_file_size)){

                //get the file size
                $file_size_in_mb = $this->bytes_to_mb($object->_file['size_in_bytes']);

                //compare file size against limit
                if($object->_max_file_size <= $file_size_in_mb){
                    $this->_validation_errors[] = 'Your file could not be uploaded, the file was larger than the upload size limit.';

                }
            }
        }


        //set file array
        private function set_file_array($file){

            //checks whether file array is valid
            if(! $this->check_file_array($file)){
                //file not selected or some bigger problems (broken files array)
                $this->_validation_errors[] = 'Your file could not be uploaded, the file is not valid.';
            }

            //set file data
            $this->_file_post = $file;

            //set tmp path
            $this->_tmp_name = $file['tmp_name'];
        }


        //check if file post array is valid
        private function check_file_array($file){

            return isset($file['error']) && ! empty($file['name']) && ! empty($file['type']) && ! empty($file['tmp_name']) && ! empty($file['size']);

        }


        //get file mime type
        private function get_file_mime(){

            return $this->_file_info->file($this->_tmp_name, FILEINFO_MIME_TYPE);
        }


        //get file size
        private function get_file_size(){

            return filesize($this->_tmp_name);
        }


        //checks if destination directory exists
        private function destination_exist(){

            return is_writable($this->_destination);
        }


        //create a unique file name
        private function create_new_filename(){

            //retain the original file extension
            $ext = pathinfo($this->_file_post['name'], PATHINFO_EXTENSION);
            //set the file name
            $this->_filename = sha1(mt_rand(1, 9999).$this->_destination.uniqid()).'.'.$ext;
        }


        //convert bytes to megabytes
        private function bytes_to_mb($bytes){

            return sprintf('%0.2f', round(($bytes / 1048576), 2));
        }

    }
