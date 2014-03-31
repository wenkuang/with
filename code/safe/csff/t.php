<?php
//从URL中获取主机名称
preg_match('@^(?:http://)?([^/]+)@i',
    "http://www.php.net/index.html", $matches);

print_r($matches);

?>