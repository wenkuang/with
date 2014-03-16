<?php
header("ContentType: text/plain;charset=utf-8");
$basedir = dirname(dirname(dirname(__FILE__)));
require_once "$basedir/models/pay.model.php";
require_once "$basedir/lib/log.class.php";
require_once "$basedir/common/config.php";
require_once "$basedir/common/common.func.php";
require_once "$basedir/oufei/cron/oufei_config.php";
require_once "$basedir/oufei/cron/oufei_huiyuan.php";

while (true) {
    //获取订单
    $orders = get_orders();
    //漏单检查
    
    //充值 确认
    
    //充值： 成功，返回
    
    //第三方失败 回滚
    
    //log
    
    //释放内存
    unset($orders);
    sleep(5);
}

$oufei_error= array(
    0000 => "操作成功",
    1001 => "参数为空",
    1002 => "签名错误",
    1003 => "模板配置异常",
    7777 => "请求频繁",
    9998 => "数据库异常",
    9999 => "系统异常"
);

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

//订单充值前确认接口
function confirm_recharge($orderid,$id){
    $data = array(
        "id" => $id, //来自之前的API返回结果
        "orderid" => $orderid,
        "format" => "json",
    );
    $url = oufei_config::$api_confirm . "?" . http_build_query($data);
    return get_api($url);
}

//充值
function set_orders($order_id,$id,$orderstate){
    $sign = strtoupper(md5(oufei_config::$partner_id  . $id . $order_id . oufei_config::$oufei_public_key));
    $data = array(
        "id" => $id, //来自之前的API返回结果
        "orderid" => $order_id,
        "sign" => $sign, //partner+id+orderId+apikey
        "partner" => oufei_config::$partner_id,
        "tplid" => oufei_config::$tplid,
        "orderstate" => $orderstate,
        "format" => "json"
    );
    $url = oufei_config::$api_setorders . "?" . http_build_query($data);
    return get_api($url);
}

//获取用户信息
function get_user_info($user_account){
    if(empty($user_account)){
        return array("error"=>-1,"参数为空");
    }
    if(is_numeric($user_account)){
        $url = oufei_config::$sina_api_by_phone . "?source=" . oufei_config::$sina_api_source . "&numbers=" . $user_account;
    }else{
        $url = oufei_config::$sina_api_by_email . "?source=" . oufei_config::$sina_api_source . "&emails=" . $user_account;
    }
    return get_api($url);
}

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

