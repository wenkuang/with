<?php
define("ROOT", dirname(__FILE__));
require_once './etc/config.php';
//安全检查
$param = $_POST['param'];
//非本站站点不能访问


//路由
$return['error'] = "0000";

switch($param){
    case "siteinfo":
        $return['data'] = array(
            "site_name" => SITE_NAME
        );
        break;
    case "":
        break;
}


echo json_encode($return);