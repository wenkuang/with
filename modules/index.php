<?php
class module{
    
    public static function __callStatic($name, $arguments) {
        $file_name = ROOT . "/modules/$name.php";
        if(is_file($file_name)){
            include $file_name;
            $m = new $name();
            return $m->get($arguments);
        }else{
            logExt::add("module: $name 不存在");
        }
        
    }
}

