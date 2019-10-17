<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // File System Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: File and directory management
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class file_system{

        //(non)-recursively iterate a directory, listing all files (utilises SPL functions which can confuse IDEs)
        public function file_iterator($dir, $recursive = true){

            $files = array();

            if($recursive){
                foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)) as $file){
                    $files[] = $file->getFilename();
                }
            }else{
                foreach(new \FilesystemIterator($dir) as $file){
                    if(!$file->isDir()){
                        $files[] = $file->getFilename();
                    }
                }
            }

            sort($files);

            return $files;
        }


        //recursively iterate a directory, listing all directories (utilises SPL functions which can confuse IDEs)
        public function directory_iterator($dir, $recursive = true){

            $directories = array();

            if($recursive){
                foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FileSystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $child_dir){
                    if($child_dir->isDir()){
                        $directories[] = $child_dir->getFilename();
                    }
                }
            }else{
                foreach(new \FilesystemIterator($dir) as $child_dir){
                    if($child_dir->isDir()){
                        $directories[] = $child_dir->getFilename();
                    }
                }
            }

            sort($directories);

            return $directories;
        }

        //count the number of files and folders contained within a directory
        public function object_count($dir){

            $count = array();

            $file_objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
            $count['file_count'] = iterator_count($file_objects);

            $count['directory_count'] = 0;

            foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FileSystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $child_dir){
                if($child_dir->isDir()){
                    $count['directory_count']++;
                }
            }

            $count['total_count'] = $count['file_count'] + $count['directory_count'];

            return $count;
        }


        //get the size of the directory and all it's contents
        public function directory_size($dir) {

            $size = 0;

            foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)) as $file){
                $size += $file->getSize();
            }

            return $size;
        }


        //create directory if it doesn't already exist, multiple directories will be created in path string if needed
        public function mkdir($dir, $mode = 0755){

            if(!is_dir($dir)){
                if(mkdir($dir, $mode, true)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }


        //recursive directory delete, also deletes any contained files
        public function rrmdir($dir){

            if(is_dir($dir)){
                foreach(scandir($dir) as $file){
                    if($file != '.' && $file != '..'){
                        $this->rrmdir("$dir/$file");
                    }
                }
                rmdir($dir);
            }elseif(file_exists($dir)){
                unlink($dir);
            }

            return ! is_dir($dir);
        }


        //recursive copy, will overwrite any files with matching names in destination and create folders if needed
        public function rcopy($source, $destination, $destination_mode = 0755){

            $status = true;

            if(is_dir($source)){
                if(! is_dir($destination)){
                    mkdir($destination, $destination_mode, true);
                }
                foreach(scandir($source) as $file){
                    if($file != '.' && $file != '..'){
                        if(! $this->rcopy("$source/$file", "$destination/$file")){
                            $status = false;
                        }
                    }
                }
            }elseif(file_exists($source)){
                if(! copy($source, $destination)){
                    $status = false;
                }
            }

            return $status;
        }


        //recursively change mode (permissions) of a directory and it's contents, can also be applied to a single file
        public function rchmod($path, $mode){

            if(! is_dir($path)){
                return chmod($path, $mode);
            }

            $dh = opendir($path);
            while(($file = readdir($dh)) !== false){
                if($file !== '.' && $file !== '..'){
                    $file = "$path/$file";

                    if(is_link($file)){
                        return false;
                    }
                    if(! is_dir($file) && ! chmod($file, $mode)){
                        return false;
                    }
                    if(! $this->rchmod($file, $mode)){
                        return false;
                    }
                }
            }
            closedir($dh);

            return chmod($path, $mode);
        }


        //recursively change ownership of a directory and it's contents, can also be applied to a single file
        public function rchown($path, $owner){

            if(! is_dir($path)){
                return chown($path, $owner);
            }

            $dh = opendir($path);
            while(($file = readdir($dh)) !== false){
                if($file !== '.' && $file !== '..'){
                    $file = "$path/$file";

                    if(is_link($file)){
                        return false;
                    }
                    if(! is_dir($file) && ! chown($file, $owner)){
                        return false;
                    }
                    if(! $this->rchown($file, $owner)){
                        return false;
                    }
                }
            }
            closedir($dh);

            return chown($path, $owner);
        }


        //recursively change group of a directory and it's contents, can also be applied to a single file
        public function rchgrp($path, $group){

            if(! is_dir($path)){
                return chgrp($path, $group);
            }

            $dh = opendir($path);
            while(($file = readdir($dh)) !== false){
                if($file !== '.' && $file !== '..'){
                    $file = "$path/$file";

                    if(is_link($file)){
                        return false;
                    }
                    if(! is_dir($file) && ! chgrp($file, $group)){
                        return false;
                    }
                    if(! $this->rchgrp($file, $group)){
                        return false;
                    }
                }
            }

            closedir($dh);

            return chgrp($path, $group);
        }


        //get the file system statistics of a directory or file
        public function stats($path){
            $stats = stat($path);

            if(is_dir($path)){
                //only include the stats that actually matter
                $return = array();
                $return['uid'] = $stats['uid'];
                $return['gid'] = $stats['gid'];
                //size is tricky we want the size of the contents too plus the actual size of the directory/file
                $return['size_mb'] = $this->bytes_to_mb($this->directory_size($path) + $stats['size']);
                $return['last_access'] = $stats['atime'];
                $return['last_modification'] = $stats['mtime'];
                $return['mode'] = substr(sprintf('%o', fileperms($path)), -4);

                return $return;
            }elseif(file_exists($path)){
                //only include the stats that actually matter
                $return = array();
                $return['uid'] = $stats['uid'];
                $return['gid'] = $stats['gid'];
                $return['size_mb'] = $this->bytes_to_mb($stats['size']);
                $return['last_access'] = $stats['atime'];
                $return['last_modification'] = $stats['mtime'];
                $return['mode'] = substr(sprintf('%o', fileperms($path)), -4);

                return $return;
            }else{

                return false;
            }
        }


        //check to see if the target directory exists
        public function directory_exists($path){
            if(is_dir($path)){
                return true;
            }else{
                return false;
            }
        }

        //check to see if the target file exists
        public function file_exists($path){
            if(is_file($path)){
                return true;
            }else{
                return false;
            }
        }

        //check to see if the target directory or file exists
        function object_exists($path){
            if(file_exists($path)){
                return true;
            }else{
                return false;
            }
        }


        //inspect a file's mime type
        function mime_type($path){
            if(is_file($path)){

                $file_info = new finfo();

                //if file_info could not be loaded, trigger error.
                if(! $file_info){
                    trigger_error('File Info could not be instantiated, magic could most likely not be loaded.', E_USER_ERROR);
                    return false;
                }

                $file_mime_type = $file_info->file($path, FILEINFO_MIME_TYPE);

                return $file_mime_type;

            }elseif(is_dir($path)){
                return 'directory';

            }else{
                return false;
            }
        }

        //inspect a file's mime type remotely using a url
        function remote_mime_type($url){

            //collect the file from the remote location
            $buffer = file_get_contents($url);

            if($buffer){

                $file_info = new finfo(FILEINFO_MIME_TYPE);

                //if file_info could not be loaded, trigger error.
                if(! $file_info){
                    trigger_error('File Info could not be instantiated, magic could most likely not be loaded.', E_USER_ERROR);
                    return false;
                }

                return $file_info->buffer($buffer);
            }else{
                return false;
            }
        }


        //convert bytes to megabytes
        public function bytes_to_mb($bytes){
            return sprintf('%0.2f', round(($bytes / 1048576), 2));
        }


        //change a file's extension
        public function replace_extension($filename, $new_extension){

            $info = pathinfo($filename);
            return $info['filename'].'.'.$new_extension;
        }
    }
