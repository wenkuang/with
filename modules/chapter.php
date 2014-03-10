<?php

class chapter{
    
    private $data ;
    public function get($data){
        $this->data = $data[0];
        return $this->struct();
    }
    
    private function struct(){
        return  "<ul class='home'>" .$this->eachlist() . "</ul>";
    }
    
    private function eachlist(){
        $return = "";
        if(!empty($this->data)){
            foreach($this->data as $k => $v){
                if(!isset($v->name)){
                    $v->name = $v->title;
                }
                $name=trim($v->name);
                $path = "chapter";
                if(!empty($v->is_article)){
                    $path= "post";
                }
                $content = html_entity_decode($v->content);
                $return .= "<li><label><a href='/{$path}/{$name}'><span class='label'>{$name}</span></a></label><p>{$v->description}</p></li>";
            }
        }
        return $return;
    }
}
