<?php

header("ContentType: text/plain;charset=utf-8");
$basedir = dirname(dirname(dirname(__FILE__)));
require_once "$basedir/models/pay.model.php";
include_once ("$basedir/common/config.php");
include_once ("$basedir/common/common.func.php");
include_once 'oufei_config.php';
include_once 'oufei_huiyuan.php';
include_once ("$basedir/lib/log.class.php");

$partner_id = oufei_config::$partner_id;
$tplid = oufei_config::$tplid;
$oufei_public_key = oufei_config::$oufei_public_key;
$sina_api_source = oufei_config::$sina_api_source;
$sina_api_by_phone = oufei_config::$sina_api_by_phone;
$sina_api_by_email = oufei_config::$sina_api_by_email;

$md5 = md5($partner_id . $tplid . $oufei_public_key);

$log = new CLog("OufeiGetOrderInfo");

if ($md5 == false) {


    $log->info("MD5_ERROR! : " . date("Y-m-d H:i:s -->"));

    exit();
}

$md5 = strtoupper($md5);


//for(;;){
//sleep(5);

$reqid = $partner_id . date("YmdHis");


$getOrderURL = oufei_config::$api_get_order . "?partner=" . $partner_id . "&tplid=" . $tplid . "&sign=" . $md5 . "&reqid=" . $reqid . "&format=json";

echo $getOrderURL;
echo '<br/>';

$checkOrderURL = oufei_config::$api_check_order . "?partner=" . $partner_id . "&tplid=" . $tplid . "&sign=" . $md5 . "&orderids=" . $reqid . "&format=json";

//echo $checkOrderURL;
//echo '<br/>';
//$getOrderRes = file_get_contents($getOrderURL);  //get json
$getOrderRes = oufei_config::$test_json;
$getArray = json_decode($getOrderRes, true);  //transfor json to array
//echo $getOrderRes;

$status = $getArray ['status'];



if ($status === "0000") {

    $data = $getArray['data'];
    $dataList = $data['dataList'];
    foreach ($dataList as $key => $value) {


        //$order_id = $value['order_id'];
        //$product_id = $value['product_id'];
        $order_time = $value['order_time'];
        echo $order_time;
        echo "<br/>";
        $order_num = $value['order_num'];
        echo $order_num;
        echo "<br/>";
        $product_par_value = $value['product_par_value'];
        echo $product_par_value;
        echo "<br/>";
        $ip_address = $value['order_ip'];
        echo $ip_address;
        echo "<br/>";
        $recharge_account = $value['recharge_account'];
        echo $recharge_account;
        echo "<br/>";
        $fee = $product_par_value * $order_num;
        echo $fee;
        echo "<br/>";

        $email_pattern = "/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/";
        $phone_pattern = "/^1\\d{10}$/";
        if (preg_match($email_pattern, $recharge_account)) {

            echo "email match";
            echo "<br/>";

            $res = file_get_contents($sina_api_by_email . "?source=" . $sina_api_source . "&emails=" . $recharge_account);
            echo $res;
            echo "<br/>";
            $res = json_decode($res, true);
            $arr = $res[0];
            $uid = $arr[$recharge_account];

            echo "uid=" . $uid;
            echo "<br/>";

            $oufei_huiyuan = new oufei_huiyuan($uid, $fee, $order_time, "", $ip_address);
            $result = $oufei_huiyuan->get_result();
            var_dump($result);
        }
        if (preg_match($phone_pattern, $recharge_account)) {
            echo "phone match";
            echo "<br/>";
            exit("exit");

            $res = file_get_contents($sina_api_by_phone . "?source=" . $sina_api_source . "&numbers=" . $recharge_account);
        }
    }
} else {

    $msg = $getArray ['msg'];
    $log->info("GET_ORDER_ERROR! : " . date("Y-m-d H:i:s-->") . "status ===" . $status . "  msg===" . $msg);
}



//}


exit();


