<?php

class h_menu{
    
    private $data ;
    public function get($data){
        $this->data = $data[0];
        return $this->struct();
    }
    
    private function struct(){
        return  "<ul class='h_menu'>" .$this->eachlist() . "</ul>";
    }
    
    private function eachlist(){
        $return = "";
        if(!empty($this->data)){
            foreach($this->data as $k => $v){
                $return .= "<li><a href='/category/{$v->name}' ><span></span>{$v->name}</a></li>";
            }
        }
        
        return $return;
    }
}
