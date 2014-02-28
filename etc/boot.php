<?php
/**
 *  项目启动时加载项目
 * 
 */
//加载配置文件
require_once './etc/config.php';
//常用值设置:服务器内部路径
define(CACHE_PATH, ROOT . "/Cache/" . THEME);
define(THEME_PATH, ROOT . "/Theme/" . THEME);
define(LOG_PATH, ROOT . "/Log/");
//客户端路径
$domain = $_SERVER['SERVER_NAME'];
define(THEME_URL,$domain . "/Theme/" . THEME);
//自动加载类
function __autoload($class_name){
    echo $class_name;
    $splits = preg_split("/(?=[A-Z])/",$class_name); 
    $class_path = ROOT . "/" . $splits[1] . "/" . $splits[0] .$splits[1] . ".php";
    if(is_file($class_path)){
        include_once $class_path;
    }
}
//路由器: /index.php?post=content,  /content;  book=bookname , /book/bookname ;   tag = tagname ,  /tag/tagname

$allowed_params = array(
    "home", #首页
    "post", #文章处理
    "book", #图书处理
    "tag"   #标签处理
);
$act = empty($_GET['act'])?"home":$_GET['act'];
if(in_array($act,$allowed_params) && is_string($act)){
    $cls_name = $act . "Route";
    $cls = new $cls_name;
    $cls->index();
} else{
    echo "404";
}