<pre>
<?php


$danger_site_referer = "http://www.baidu.com";
$referer =$_SERVER['HTTP_REFERER'];
$allowed_referer= "http://" . $_SERVER ['HTTP_HOST'];
echo $allowed_referer;
$c = preg_match('@^(?:http://)?([^/]+)@i',
    "$referer", $matches);
$host = $matches[1];
echo $host;
//$t = preg_match("/[http://sms.sina.com.cn]/",$referer,$matches);
//echo $allowed_referer;
//echo $referer;



