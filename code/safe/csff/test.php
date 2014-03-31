<pre>
<?php


$danger_site_referer = "http://sms.sina.com.cn/134314/index.php";
$referer ="http://www.baidu.com/dasd.php";$_SERVER['HTTP_REFERER'];

$allowed_referer=  $_SERVER ['HTTP_HOST'];

var_dump(csrf::check_referer($danger_site_referer));

class csrf{
    
    public static function check_referer($referer="",$domain = ""){
        #请求来源网址
        $referer =empty($referer)?$_SERVER['HTTP_REFERER']:$referer;
        
        #当前服务器域名，
        $allowed_domain = empty($domain)?$_SERVER ['HTTP_HOST']:$domain;
        if(!empty($referer)){
            
            preg_match('@^(?:http://)?([^/]+)@i',"$referer", $matches);

            if(!empty($matches[1]) && $matches[1] == $allowed_domain){
                return true;
            }
            return false;
        }
        
        return false;
        
    }
    
    
}



