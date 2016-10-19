<?php 
    class stuff
    {
        // public function __construct($db){
        //     $this->db = $db;           
        // }
        public function create_zip($files = array(),$destination = '',$overwrite = false) {
            //if the zip file already exists and overwrite is false, return false
            if(file_exists($destination) && !$overwrite) { return false; }
            //vars
            $valid_files = array();
            //if files were passed in...
            if(is_array($files)) {
                //cycle through each file
                foreach($files as $file) {
                    //make sure the file exists
                    if(file_exists($file)) {
                        $valid_files[] = $file;
                    }
                }
            }
            //if we have good files...
            if(count($valid_files)) {
                //create the archive
                $zip = new ZipArchive();
                if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                    return false;
                }
                //add the files
                foreach($valid_files as $file) {
                    $zip->addFile($file,basename($file));
                }
                //debug
                //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
                //close the zip -- done!
                $zip->close();
                //check to make sure the file exists
                return file_exists($destination);
            }
            else
            {
                return false;
            }
        }
        public function clean_link($text) {
           $text = strtolower($text);
           //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
           //Strip any unwanted characters
           $text = preg_replace("/[^a-z0-9_\s-]/", "", $text);
           //Clean multiple dashes or whitespaces
           $text = preg_replace("/[\s-]+/", " ", $text);
           //Convert whitespaces and underscore to dash
           $text = preg_replace("/[\s_]/", "-", $text);
           $replace = array(" ( ", " ) ", ',', ' ', '`', "'", '"', "{", "}", "[", "]", "=", "*", "(", ")", "&","ă","î","ș","ț","â","Ă","Î","Ș","Ț","Â");
           $replace2 = array("?", "!", ".");
           $text = str_replace($replace, '-', $text);
           $text = str_replace($replace2, '', $text);
           return $text;
        }
        
    }
?>