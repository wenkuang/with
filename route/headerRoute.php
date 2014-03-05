<?php
/**
 * 路由头
 */
class headerRoute{
    public $data;

    public function __construct() {
        $this->data = array(
            "class_name" => get_class(),
            "domain" => DOMAIN,
            "site_name" => SITE_NAME,
            "site_url"=>SITE_URL
        );
        //记录访问日志
    }
    
    //展示模板
    public function show($template_name){
        //调试工具
        debugExt::push("页面加载时的数据", $this->data);
        //优先访问缓存，如果无缓存生成再访问
        $cache_file = CACHE_PATH . "/" . $template_name  . ".html";
        if(!DEBUG && is_file($cache_file)){
            include $cache_file;
            return;
        }
        //生成缓存
        $cacheExt = new cacheExt();
        if($cacheExt->create($template_name,  $this->data)){
            include $cache_file;
        }else{
            //直接访问php文件
            include THEME_PATH  . "/" . $template_name  . ".html";
            logExt::add("error | 无缓存生成，检查CACHE目录权限");
        }
    }
    
    private function get_class_name(){
        return get_class($this);
    }

    public function __destruct() {
        debugExt::show();
    }
    
    
    
}

