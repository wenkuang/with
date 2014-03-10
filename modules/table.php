<?php

class table{
    
    private $data ;
    public function get($data){
        $this->data = $data[0];
        return $this->struct();
    }
    
    private function struct(){
        return  "<table>" .$this->eachlist() . "</table>";
    }
    
    private function eachlist(){
        $return = "";
        if(!empty($this->data)){
            foreach($this->data as $k => $v){
                if(!isset($v->name)){
                    $v->name = $v->title;
                }
                $name=trim($v->name);
                $return .= "<tr><td><a href='/post/{$name}'>{$name}</a></td></tr>";
            }
        }
        
        return $return;
    }
}
