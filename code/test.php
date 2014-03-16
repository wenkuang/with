<?php
header("ContentType: text/plain;charset=utf-8");
$basedir = dirname(dirname(dirname(__FILE__)));
require_once "$basedir/models/pay.model.php";
require_once "$basedir/lib/log.class.php";
require_once "$basedir/common/config.php";
require_once "$basedir/common/common.func.php";
require_once "$basedir/oufei/cron/oufei_config.php";
require_once "$basedir/oufei/cron/oufei_huiyuan.php";

$sign = strtoupper(md5(oufei_config::$partner_id  . oufei_config::$tplid . oufei_config::$oufei_public_key));
$reqid = oufei_config::$partner_id . time();
$url = oufei_config::$api_get_order . "?partner=" . oufei_config::$partner_id . "&tplid="
         . oufei_config::$tplid ."&sign=$sign&reqid=$reqid&format=json";




//初始化 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$output = curl_exec($ch);
curl_close($ch);