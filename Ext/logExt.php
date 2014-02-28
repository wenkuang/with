<?php

class logExt{
    
    public function add($log_content,$log_type){
        $log_file = LOG_PATH . date("Y-m-d") .".log";
        $log_content = date("Y-m-d H:i:s") . "|" . $_SERVER['REMOTE_ADDR'] . " | " .$_SERVER['REQUEST_URI'] . " | " . $log_content . "\t\n";
        file_put_contents($log_file, $log_content . "\t\n",FILE_APPEND);
    }
    
}