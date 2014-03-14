<?php
/*
* @link http://www.kalcaddle.com/
* @author warlee | e-mail:kalcaddle@qq.com
* @copyright warlee 2014.(Shanghai)Co.,Ltd
* @license http://kalcaddle.com/tools/licenses/license.txt
*/

//处理成用户目录，并且不允许相对目录的请求操作
function _DIR($path,$pre_path=HOME){
    $path = rawurldecode($path);
    $path = str_replace('\\','/',trim($path));
    if (strstr($path,'../')) {//preg耗性能
        $path = preg_replace('/\.+\/+/', '/', $path);
        $path = preg_replace('/\/+/', '/', $path);
    }
    $path = iconv_system($path);
    if (is_dir($path)) $path.='/';
    return str_replace('//','/',$pre_path.$path);
}

//处理成用户目录，并且不允许相对目录的请求操作
function _DIR_OUT(&$arr,$pre_path=HOME){
    if ($GLOBALS['is_root']) return;
    if (is_array($arr)) {
        foreach ($arr['filelist'] as $key => $value) {
            $arr['filelist'][$key]['path'] = '/'.str_replace(HOME, '', $value['path']);
        }
        foreach ($arr['folderlist'] as $key => $value) {
            $arr['folderlist'][$key]['path'] = '/'.str_replace(HOME, '', $value['path']);
        }        
    }else{
        $arr = str_replace(HOME, '',$arr);
    }
}

//处理成url连接；返回是否是在web路径下
function _URL($path,$pre_path=HOME){
    $path = str_replace('\\','/',rawurldecode(trim($path)));
    $path = rtrim($path,'/');
    if (strstr($path,'../')) {//preg耗性能
        $path = preg_replace('/\.+\/+/', '/', $path);
        $path = preg_replace('/\/+/', '/', $path);
    }
    $path = $pre_path.$path;
    $path = iconv_system($path);
    if (substr($path,0,strlen(WEB_ROOT)) == WEB_ROOT) {
        return array(true,HOST.str_replace(WEB_ROOT, '', $path));
    }else{
        return array(false,$pre_path.$path);
    }
}

function init_lang(){
    if (DEFAULT_LANGUAGE == '') {
        preg_match('/^([a-z\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
        $lang = $matches[1];
        switch (substr($lang,0,2)) {
            case 'zh':
                if ($lang != 'zn-TW'){
                    $lang = 'zh-CN';
                } 
                break;
            case 'en':$lang = 'en';break;                
            default:$lang = 'en';break;
        }
    }else{
        $lang = DEFAULT_LANGUAGE;
    }
    $lang = str_replace('-', '_',$lang);
    define('LANGUAGE_TYPE', $lang);
    include(LANGUAGE_PATH.$lang.'/main.php');
    $GLOBALS['L'] = $L;
    $GLOBALS['lang'] = $L;
}