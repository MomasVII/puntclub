<?php
///////////////////////////////////////////////////////////////////////////////////
// Compile Library
// Build: Rare_PHP_Core_Framework
// Purpose: CSS and JS basic compilation
// Version 1.0.1
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
///////////////////////////////////////////////////////////////////////////////////

class web_compile extends file_system
{

    //append a version number to force cache clear
    private $version = '?v=2';

    //set the path to compiled files
    private $compile_path = ROOT.'web/dyn_swap/';

    //set switch to force compiler to always run on every call
    private $force_compile = false;

    //make sure the compile swap directory exists on load
    public function __construct()
    {

        //if the global force switch is set to true, trip the local private switch
        if (FORCE_COMPILE) {
            $this->force_compile = true;
        }

        //look for the compile swap directory
        if (!$this->directory_exists($this->compile_path)) {

            //attempt to create the swap directory
            if (!$this->mkdir($this->compile_path)) {
                trigger_error('Swap directory couldn\'t be found or created, the front-end is going to break outside of local environments', E_USER_ERROR);
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    //check if a compiled file exists for the current URI
    private function compile_exists($compile_name)
    {
        $return = array(
            'boolean' => false,
            'content' => '',
        );

        //explode the complied file name to separate EPOCH string
        $name_chunks = explode('__', $compile_name);
        if (isset($name_chunks[0]) && !empty($name_chunks[0])) {

            //search search string
            $search_string = $name_chunks[0];

            //search the directory for files with a matching string
            $search_results = glob($this->compile_path.$search_string.'*');
            if (is_array($search_results) && !empty($search_results)) {

                //reverse array because we want the newest version of the compiled file if there's multiples
                $search_results = array_reverse($search_results);

                //double check our work
                if (isset($search_results[0]) && !empty($search_results[0])) {
                    $return['content'] = array_shift($search_results);
                    $return['boolean'] = true;

                    //clean up any duplicates
                    $duplicates = $search_results;
                    if (!empty($duplicates) && is_array($duplicates)) {

                        //loop through duplicates and delete them
                        foreach ($duplicates as $k => $v) {
                            unlink($v);
                        }
                    }
                }
            }
        }

        return $return;
    }

    //make JS as small as we can without breaking it
    private function js_string_compress($js)
    {

        //set regex patterns
        $replace = array(
            '#\'([^\n\']*?)/\*([^\n\']*)\'#'    =>  "'\1/'+\'\'+'*\2'", //remove comments from ' strings
            '#\"([^\n\"]*?)/\*([^\n\"]*)\"#'    =>  '"\1/"+\'\'+"*\2"', //remove comments from " strings
            '#/\*.*?\*/#s'                      =>  "", //strip C style comments
            '#[\r\n]+#'                         =>  "\n", //remove blank lines and \r's
            '#\n([ \t]*//.*?\n)*#s'             =>  "\n", //strip line comments (whole line only)
            '#([^\\])//([^\'"\n]*)\n#s'         =>  "\\1\n", //strip line comments
            '#\n\s+#'                           =>  "\n", //strip excess whitespace
            '#\s+\n#'                           =>  "\n", //strip excess whitespace
            '#(//[^\n]*\n)#s'                   =>  "\\1\n", //extra line feed after any comments left
            '#/([\'"])\+\'\'\+([\'"])\*#'       =>  "/*" //restore comments in strings
        );

        //run regex patterns
        $search = array_keys($replace);
        //TODO: resolve compression bugs
        //$js = preg_replace($search, $replace, $js);

        //set replace patterns
        $replace = array(
            "})\n"  =>  "});",
            "&&\n"  =>  "&&",
            "||\n"  =>  "||",
            ");\n"  =>  ");",
            "(\n"   =>  "(",
            ")\n"   =>  ")",
            "[\n"   =>  "[",
            "]\n"   =>  "]",
            "+\n"   =>  "+",
            ",\n"   =>  ",",
            "?\n"   =>  "?",
            ":\n"   =>  ":",
            ";\n"   =>  ";",
            "{\n"   =>  "{",
            "\n]"   =>  "]",
            "\n)"   =>  ")",
            "\n}"   =>  "}",
            "\n\n"  =>  "\n"
        );

        //run replace patterns
        $search = array_keys($replace);
        //TODO: resolve compression bugs
        //$js = str_replace($search, $replace, $js);

        return $js;
    }

    //compile js array into a single bundle and return tag reference for front-end
    public function build_js($script_string, $prefix = '')
    {
        global $shortcut;

        //define return var
        $return_string = '';

        //make sure the styles constant exists
        if (!empty($script_string)) {

            //strip all whitespace out of STYLES
            $no_white = trim($script_string);
            $no_white = $string = preg_replace('/\s+/', '', $no_white);

            //turn styles string into an array
            $script_array = explode(',', $no_white);

            //don't compile on local, deliver js array references as individual css calls to make debugging easier
            /*if (ENVIRONMENT !== 'local' || $this->force_compile) {

                //get the current URI
                $compile_name = $shortcut->clean_uri($_SERVER['REQUEST_URI']);

                //if the URI is blank then we're on index.html
                if ($compile_name == '/' || $compile_name == '') {
                    $compile_name = '/index.html';
                }

                //make the uri file system safe
                $compile_name = str_replace('/', '', $compile_name);
                $compile_name = str_replace('.', '_', $compile_name);

                //append the time and file type
                $compile_name = $prefix.'js_'.$compile_name.'__'.mktime(0, 0, 0).'.js';

                //if the compiled file doesn't exist or we're on dev, compile it (dev re-compiles every load)
                $already_compiled = $this->compile_exists($compile_name);
                if (!$already_compiled['boolean'] || ENVIRONMENT == 'dev' || $this->force_compile) {

                    //set container for compiled contents
                    $compile_string = '';

                    //loop through script
                    foreach ($script_array as $k => $v) {
                        if ($v !== '') {

                            //make sure the source js file exists
                            if ($this->file_exists($v)) {

                                //read out contents of file
                                if (is_readable($v)) {

                                    //attempt to open the file
                                    if ($fh = fopen($v, 'r')) {
                                        $fc = fread($fh, filesize($v));
                                        fclose($fh);

                                        //strip all whitespace out of file contents
                                        $no_white = trim($fc);
                                        $no_white = $this->js_string_compress($no_white);

                                        $compile_string .= $no_white;
                                    }
                                }
                            } else {
                                //is the script reference a URL
                                $is_http = strpos($v, 'http://');
                                $is_https = strpos($v, 'https://');

                                //the script may be hosted externally
                                if ($is_http !== false || $is_https !== false) {

                                    //set external JS html reference
                                    $return_string .= '<script type="text/javascript" src="' .$v. '"></script>' . "\r\n";
                                }
                            }
                        }
                    }

                    //make sure the compiled string isn't empty
                    if (!empty($compile_string)) {

                        //attempt to open a write file handle
                        $fh = fopen($this->compile_path.$compile_name, 'w');
                        if ($fh) {

                            //lock file exclusively
                            flock($fh, LOCK_EX);

                            //write compiled string into content
                            fwrite($fh, $compile_string);

                            //flush the write handle's output buffer
                            fflush($fh);

                            //release lock
                            flock($fh, LOCK_UN);

                            //close the file handle
                            fclose($fh);

                            //double check our work
                            if ($this->file_exists($this->compile_path.$compile_name)) {

                                //set compiled JS html reference
                                if ($prefix === 'head') {
                                    $return_string .= '<script rel="preload" as="script" type="text/javascript" src="' .$this->compile_path.$compile_name. '"></script>' . "\r\n";
                                } else {
                                    $return_string .= '<script defer type="text/javascript" src="' .$this->compile_path.$compile_name. '"></script>' . "\r\n";
                                }
                            }
                        }
                    }
                } else {
                    //return the reference to the already compiled JS
                    $return_string .= '<script type="text/javascript" src="' . $already_compiled['content'] . '"></script>' . "\r\n";

                    //loop through script to find external references
                    foreach ($script_array as $k => $v) {
                        if ($v !== '') {

                            //is the script reference a URL
                            $is_http = strpos($v, 'http://');
                            $is_https = strpos($v, 'https://');

                            //the script may be hosted externally
                            if ($is_http !== false || $is_https !== false) {

                                //set external JS html reference
                                $return_string .= '<script type="text/javascript" src="' .$v. '"></script>' . "\r\n";
                            } else {
                                continue;
                            }
                        }
                    }
                }
            } else {*/

                //loop through scripts
                foreach ($script_array as $k => $v) {
                    if ($v !== '') {

                        //append source JS html references to return string
                        if ($prefix === 'head') {
                            $return_string .= '<script type="text/javascript" rel="preload" as="script" src="' . $v . $this->version . '"></script>' . "\r\n";
                        } else {
                            $return_string .= '<script defer type="text/javascript" src="' . $v . $this->version . '"></script>' . "\r\n";
                        }
                    }
                }
            /*}*/
        }

        //return a tidy JS resource(list) ready for the front-end
        return trim($return_string);
    }

    //make CSS as small as we can without breaking it
    private function css_string_compress($css)
    {

        //set regex patterns
        $replace = array(
            '#/\*.*?\*/#s'  =>  "", //strip C style comments.
            '#\s\s+#'       =>  " ", //strip excess whitespace.
        );

        //run regex patterns
        $search = array_keys($replace);
        $css = preg_replace($search, $replace, $css);

        //set replace patterns
        $replace = array(
            ": "    =>  ":",
            "; "    =>  ";",
            " {"    =>  "{",
            " }"    =>  "}",
            ", "    =>  ",",
            "{ "    =>  "{",
            ";}"    =>  "}", //strip optional semicolons.
            ",\n"   =>  ",", //don't wrap multiple selectors.
            "\n}"   =>  "}", //don't wrap closing braces.
            "} "    =>  "}\n", //put each rule on it's own line.
        );

        //run replace patterns
        $search = array_keys($replace);
        $css = str_replace($search, $replace, $css);

        return $css;
    }


    //compile css array into a single bundle and return tag reference for front-end
    public function build_css($style_string, $prefix = '')
    {
        global $shortcut;

        //define return var
        $return_string = '';

        //make sure the styles constant exists
        if (!empty($style_string)) {

            //strip all whitespace out of STYLES
            $no_white = trim($style_string);
            $no_white = $string = preg_replace('/\s+/', '', $no_white);

            //turn styles string into an array
            $style_array = explode(',', $no_white);

            //don't compile on local, deliver css array references as individual css calls to make debugging easier
            /*if (ENVIRONMENT !== 'local' || $this->force_compile) {

                //get the current URI
                $compile_name = $shortcut->clean_uri($_SERVER['REQUEST_URI']);

                //if the URI is blank then we're on index.html
                if ($compile_name == '/' || $compile_name == '') {
                    $compile_name = '/index.html';
                }

                //make the uri file system safe
                $compile_name = str_replace('/', '', $compile_name);
                $compile_name = str_replace('.', '_', $compile_name);

                //append the time and file type
                $compile_name = $prefix.'css_'.$compile_name.'__'.mktime(0, 0, 0).'.css';

                //if the compiled file doesn't exist or we're on dev, compile it (dev re-compiles every load)
                $already_compiled = $this->compile_exists($compile_name);
                if (!$already_compiled['boolean'] || ENVIRONMENT == 'dev' || $this->force_compile) {

                    //set container for compiled contents
                    $compile_string = '';

                    //loop through style
                    foreach ($style_array as $k => $v) {
                        if ($v !== '') {

                            //make sure the source css file exists
                            if ($this->file_exists($v)) {

                                //read out contents of file
                                if (is_readable($v)) {

                                    //attempt to open the file
                                    if ($fh = fopen($v, 'r')) {
                                        $fc = fread($fh, filesize($v));
                                        fclose($fh);

                                        //strip all whitespace out of file contents
                                        $no_white = trim($fc);
                                        $no_white = $this->css_string_compress($no_white);

                                        $compile_string .= $no_white;
                                    }
                                }
                            } else {
                                //is the style reference a URL
                                $is_http = strpos($v, 'http://');
                                $is_https = strpos($v, 'https://');

                                //the style may be hosted externally
                                if ($is_http !== false || $is_https !== false) {

                                    //set external css html reference
                                    $return_string .= '<link rel="stylesheet" type="text/css" href="' .$v. '" />' . "\r\n";
                                }
                            }
                        }
                    }

                    //make sure the compiled string isn't empty
                    if (!empty($compile_string)) {

                        //attempt to open a write file handle
                        $fh = fopen($this->compile_path.$compile_name, 'w');
                        if ($fh) {

                            //lock file exclusively
                            flock($fh, LOCK_EX);

                            //write compiled string into content
                            fwrite($fh, $compile_string);

                            //flush the write handle's output buffer
                            fflush($fh);

                            //release lock
                            flock($fh, LOCK_UN);

                            //close the file handle
                            fclose($fh);

                            //double check our work
                            if ($this->file_exists($this->compile_path.$compile_name)) {

                                //set compiled CSS html reference
                                $return_string .= '<link rel="stylesheet" type="text/css" href="'.$this->compile_path.$compile_name.'" />' . "\r\n";
                            }
                        }
                    }
                } else {
                    //return the reference to the already compiled CSS
                    $return_string .= '<link rel="stylesheet" type="text/css" href="' . $already_compiled['content'] . '" />' . "\r\n";

                    //loop through style to find external references
                    foreach ($style_array as $k => $v) {
                        if ($v !== '') {

                            //is the style reference a URL
                            $is_http = strpos($v, 'http://');
                            $is_https = strpos($v, 'https://');

                            //the style may be hosted externally
                            if ($is_http !== false || $is_https !== false) {

                                //set external CSS html reference
                                $return_string .= '<link rel="stylesheet" type="text/css"  href="' . $v . '" />' . "\r\n";
                            } else {
                                continue;
                            }
                        }
                    }
                }
            } else {*/

                //loop through styles
                foreach ($style_array as $k => $v) {
                    if ($v !== '') {

                        //append source CSS html references to return string
                        $return_string .= '<link rel="stylesheet" type="text/css" href="' . $v . $this->version . '" />' . "\r\n";
                    }
                }
            /*}*/
        }

        //return a tidy css resource(list) ready for the front-end
        return trim($return_string);
    }
}
