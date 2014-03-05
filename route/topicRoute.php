<?php

class topicRoute extends headerRoute{
    
    public function index(){
        include 'modules/index.php';
        $category = new categoryModel();
        $menu = $category->where("1=1")->select()->limit(10)->result['data'];
        $url = new urlExt();
        $topic_name = $url->get_param("name");
        $topic = new bookModel();
        $topic_info = $topic->where("name like '%$topic_name%'")->select()->result['data'][0];
        $topic_id = $topic_info->id;
        $chapter = new chapterModel();
        $chapter_info = $chapter->where("book_id = $topic_id")->select()->result['data'];
        $this->data['chapters'] = module::item($chapter_info);
        $this->data['menu'] = module::menu($menu);
        $this->show("book");
    }
}
