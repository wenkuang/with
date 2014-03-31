<pre>
<?php


$danger_site_referer = "http://www.baidu.com";
$referer =$_SERVER['HTTP_REFERER'];
$allowed_referer= "http://" . $_SERVER ['HTTP_HOST'];
echo $allowed_referer;
echo $referer;
if(!empty($referer) ){
    
    
}



