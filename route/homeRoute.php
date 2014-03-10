<?php

class homeRoute extends headerRoute{
    
    public function index(){
        include 'modules/index.php';
        $category = new categoryModel();
        $menu = $category->where("parent_id = 0")->select()->result['data'];
        $topic = new chapterModel();
        $topic_list = $topic->where("is_article=1")->orderby("id desc")->limit(30)->select()->result['data'];
        $this->data['topics'] = module::home($topic_list);
        $this->data['menu'] = module::h_menu($menu);
        
        $this->show("index");
    }
}
