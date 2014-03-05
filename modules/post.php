<?php

class post{
    
    private $data ;
    public function get($data){
        $this->data = $data[0];
        return $this->struct();
    }
    
    private function struct(){
        return  "<ul class='post'>" .$this->eachlist() . "</ul>";
    }
    
    private function eachlist(){
        $return = "";
        if(!empty($this->data)){
            foreach($this->data as $k => $v){
                if(!isset($v->name)){
                    $v->name = $v->title;
                }
                $name=trim($v->name);
                $content = html_entity_decode($v->content);
                $return .= "<li><label><a href='/post/{$name}'><span class='label'>{$name}</span></a></label><p>{$content}</p></li>";
            }
        }
        return $return;
    }
}
