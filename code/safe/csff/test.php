<pre>
<?php


$danger_site_referer = "http://www.baidu.com";
$referer ="http://www.1.wctest.sinaapp.com/csrfsina/shouquan.html";$_SERVER['HTTP_REFERER'];
$allowed_referer=  $_SERVER ['HTTP_HOST'];
echo $allowed_referer;
$c = preg_match('@^(?:http://)?([^/]+)@i',
    "$referer", $matches);

print_r($matches);



