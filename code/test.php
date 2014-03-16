<?php
header("ContentType: text/plain;charset=utf-8");
$basedir = dirname(dirname(dirname(__FILE__)));
require_once "$basedir/models/pay.model.php";
require_once "$basedir/lib/log.class.php";
require_once "$basedir/common/config.php";
require_once "$basedir/common/common.func.php";
require_once "$basedir/oufei/cron/oufei_config.php";
require_once "$basedir/oufei/cron/oufei_huiyuan.php";


//获取所有的订单
function get_orders(){
    //新浪key ？
    $sign = strtoupper(md5(oufei_config::$partner_id  . oufei_config::$tplid . oufei_config::$oufei_public_key));
    $reqid = oufei_config::$partner_id . time();
    $data = array(
        "partner" => oufei_config::$partner_id,
        "tplid" => oufei_config::$tplid,
        "sign" => $sign,//partner+tplid+apikey
        "reqid" => $reqid,
        "format" => "json"
    );  
    $url = oufei_config::$api_get_order . "?" . http_build_query($data);
    return get_api($url);
}

//检查订单
function check_orders($reqid,$orderids){
    $sign = strtoupper(md5(oufei_config::$partner_id  . oufei_config::$tplid . $reqid . oufei_config::$oufei_public_key));
    $data = array(
        "partner" => oufei_config::$partner_id,
        "tplid" => oufei_config::$tplid,
        "sign" => $sign,    //partner+tplid+reqId+apikey
        "reqid" => $reqid,  //来自于第一次请求返回的值
        "orderids" => $orderids,
        "format" => "json",
        
    );
    $url = oufei_config::$api_check_order . "?" . http_build_query($data);
    return get_api($url);
}

//

//获取API
function get_api($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $return = curl_exec($ch);
    curl_close($ch);
    return json_decode($return);
}

