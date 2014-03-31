<pre>
<?php

//调用方法: 
$danger_site_referer = "http://www.baidu.com/134314/index.php";
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



