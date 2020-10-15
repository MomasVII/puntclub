<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // Image GD Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: GD based image management
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class image_gd{

        public $image; //image container in memory
        public $width; //image width in px
        public $height; //image height in px
        public $type; //IMAGETYPE_XXX for matching purposes
        public $attributes; //width and height as a string ready to be put into an IMG tag
        public $mime_type; //mime type of image


        //allow image load on instantiation
        public function __construct($filename = null){

            if(! empty($filename)){
                $this->load($filename);
            }
        }

        //load an image into memory and get it's specs
        public function load($file){

            //kill any previous image that might still be in memory before we load a new one
            if(isset($this->image) && !empty($this->image)){
                imagedestroy($this->image);
            }

            //get the parameters of the image
            $image_params = getimagesize($file);

            //save the image params to class vars
            list($this->width, $this->height, $this->type, $this->attributes) = $image_params;
            $this->mime_type = $image_params['mime'];

            //check that an image type was found
            if(isset($this->type) && !empty($this->type)){
                //find the type of image so it can be loaded into memory
                switch ($this->type) {
                    case IMAGETYPE_JPEG:
                        $this->image = imagecreatefromjpeg($file);
                        break;

                    case IMAGETYPE_GIF:
                        $this->image = imagecreatefromgif($file);
                        break;

                    case IMAGETYPE_PNG:
                        $this->image = imagecreatefrompng($file);
                        break;

                }

                if(isset($this->image) && !empty($this->image)){
                    return true;
                }else{
                    return true;
                }
            }else{
                return false;
            }
        }

        //save an image to location, uses $this->image, $file is the location to save to
        public function save($file, $image_type = IMAGETYPE_JPEG, $compression = 90, $mode = 0700){

            //bring dependant libraries into scope
            global $file_system;

            $return = false;

            switch ($image_type) {
                case IMAGETYPE_JPEG:
                    if(imagejpeg($this->image, $file, $compression)){
                        $return = true;
                    }

                    break;

                case IMAGETYPE_GIF:
                    if(imagegif($this->image, $file, $compression)){
                        $return = true;
                    }

                    break;

                case IMAGETYPE_PNG:
                    if(imagepng($this->image, $file, $compression)){
                        $return = true;
                    }

                    break;

            }

            $file_system->rchmod($file, $mode);

            return $return;
        }

        //header out an image
        public function output($image_type = IMAGETYPE_JPEG, $quality = 90){

            switch ($image_type) {
                case IMAGETYPE_JPEG:
                    header('Content-type: image/jpeg');
                    imagejpeg($this->image, null, $quality);
                    break;

                case IMAGETYPE_GIF:
                    header('Content-type: image/gif');
                    imagegif($this->image);
                    break;

                case IMAGETYPE_PNG:
                    header('Content-type: image/png');
                    imagepng($this->image);
                    break;

            }

            //destroy image in memory and exit after header
            imagedestroy($this->image);
            exit;
        }

        public function get_width() {

            $this->width = imagesx($this->image);
            return $this->width;
        }

        public function get_height() {

            $this->height = imagesy($this->image);
            return $this->height;
        }

        //resize by height maintaining proportions
        public function resize_by_height($height){

            $ratio = $height / $this->height;
            $width = $this->width * $ratio;
            $this->resize($width, $height);

            return true;
        }

        //resize by width maintaining proportions
        function resize_by_width($width){

            $ratio = $width / $this->width;
            $height = $this->height * $ratio;
            $this->resize($width, $height);

            return true;
        }

        //create a square image with defined sizes from any image
        function resize_square($size){

            //container for new image
            $new_image = imagecreatetruecolor($size, $size);


            if($this->width > $this->height){
                $this->resize_by_height($size);

                imagecolortransparent($new_image, imagecolorallocate($new_image, 0, 0, 0));
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
                imagecopy($new_image, $this->image, 0, 0, ($this->get_width() - $size) / 2, 0, $size, $size);
            }else{
                $this->resize_by_width($size);

                imagecolortransparent($new_image, imagecolorallocate($new_image, 0, 0, 0));
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
                imagecopy($new_image, $this->image, 0, 0, 0, ($this->get_height() - $size) / 2, $size, $size);
            }

            $this->image = $new_image;

            //reset sizes
            $this->width = $size;
            $this->height = $size;

            return true;
        }

        //resize and image by scale
        function resize_scale($scale){

            $width = $this->get_width() * $scale / 100;
            $height = $this->get_height() * $scale / 100;
            $this->resize($width, $height);

            return true;
        }

        //free resize an image to any pixel dimension
        function resize($width, $height){

            $new_image = imagecreatetruecolor($width, $height);

            imagecolortransparent($new_image, imagecolorallocate($new_image, 0, 0, 0));
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);

            imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->get_width(), $this->get_height());
            $this->image = $new_image;

            //reset sizes
            $this->width = $width;
            $this->height = $height;

            return true;
        }

        //crop an image with x/y w/h pixel anchors and dimensions
        function crop($x, $y, $width, $height){

            $new_image = imagecreatetruecolor($width, $height);
            imagecopy($new_image, $this->image, 0, 0, $x, $y, $width, $height);
            $this->image = $new_image;

            //reset sizes
            $this->width = $width;
            $this->height = $height;

            return true;
        }

        //remove image from memory
        public function __destruct(){

            if(isset($this->image) && !empty($this->image)){
                imagedestroy($this->image);
            }
        }

    }