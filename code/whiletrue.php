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
