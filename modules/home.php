<?php

class home{
    
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
                $v->description = $v->description . "今日盛大游戏与中青宝宣布拟在中国（上海）自由贸易试验区合资成立网络游戏公司。这家公司或将成为自贸区首家网游公司，而《战争世界》是这家合资公司的第一个项目。据了解，成立后的该公司将专业从事游戏的服务贸易拓展，涉及金融服务、商贸服务、专业服务、文化服务、社会服务等多个领域。双方表示将利用自贸区机遇，深入推进全球化战略，促进商业模式创新升级转型。";
                $return .= "<li><a href='/post/{$name}'>{$name}</a><p>{$v->description}</p></li>";
            }
        }
        
        return $return;
    }
}
