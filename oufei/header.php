<?php
$basedir = "/data0/apache/htdocs/pay.mobile.sina.cn/ota/weibo_huiyuan";
require_once "$basedir/common/config.php";
require_once "$basedir/oufei/cron/oufei_config.php";
require_once "$basedir/common/init.php";
include_once '/usr/local/apache/htdocs/weibo_platform/oufei/oufei.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>欧飞平台</title>
    <link href="flat_ui/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Loading Flat UI -->
    <script src="flat_ui/js/jquery-1.10.2.min.js"></script>
<script src="flat_ui/js/bootstrap.min.js"></script>
    <link href="flat_ui/css/flat-ui.css" rel="stylesheet">
     <link href="flat_ui/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="flat_ui/images/favicon.ico">
    <script src="flat_ui/js/bootstrap-datetimepicker.min.js"></script>
    <style type="text/css">
        body{
            font-family:"Microsoft Yahei";
            font-size:15px;
        }
        table{
            margin:25px 10px;
        }
        .container{padding-top:70px;}
    </style>
  </head>
  <body>
  <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
 <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-01">
      <span class="sr-only"></span>
    </button>
    <a class="navbar-brand" href="#">欧飞平台</a>
  </div>
  <div class="collapse navbar-collapse" id="navbar-collapse-01">
    <ul class="nav navbar-nav">           
      <li class="active"><a href="#fakelink">订单</a></li>
      <li><a href="#fakelink">日志</a></li>
      <li><a href="#fakelink">统计</a></li>
    </ul>           
    <form class="navbar-form navbar-right" action="#" role="search">
      <div class="form-group">
        <div class="input-group">
          <input class="form-control" id="navbarInput-01" type="search" placeholder="Search">
          <span class="input-group-btn">
            <button type="submit" class="btn"><span class="fui-search"></span></button>
          </span>            
        </div>
      </div>               
    </form>
  </div>
</nav>