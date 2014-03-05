<?php

class urlExt{
    public function get_param($key){
      if (!isset($key) OR empty($key) OR !is_string($key))
          return false;
      $ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $defaultValue));

      if (is_string($ret) === true)
          $ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
      return !is_string($ret) ? $ret : stripslashes($ret);  
    }  
}
