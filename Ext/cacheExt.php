<?php

class cacheExt extends headerRoute{
    
    public function create($template_name,$template_data){
        $cache_file = CACHE_PATH  . $template_name  . ".html";
        $data = $this->parse($template_name,$template_data);
        return file_put_contents($cache_file , $data);
    }
    
    public function parse($template_name,$template_data){
        $source_file =  THEME_PATH  . "/" . $template_name  . ".html";
        $content = file_get_contents($source_file);
        if(!empty($template_data)){
            foreach($template_data as $k => $v){
                $k = "{{{$k}}}";
               $content = str_replace("$k", "$v", $content);
            }
        }
        return $content;
    }
    
}