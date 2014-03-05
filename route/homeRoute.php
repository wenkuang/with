<?php

class homeRoute extends headerRoute{
    
    public function index(){
        include 'modules/index.php';
        $category = new categoryModel();
        $menu = $category->where("1=1")->select()->limit(10)->result['data'];
        $topic = new bookModel();
        $topic_list = $topic->where("1=1")->select()->limit(10)->result['data'];
        $this->data['topics'] = module::blocklist($topic_list);
        $this->data['menu'] = module::menu($menu);
        $this->show("index",  $this->data);
    }
}
