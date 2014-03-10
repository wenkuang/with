<?php

class topicRoute extends headerRoute{
    
    public function index(){
        include 'modules/index.php';
        $category = new categoryModel();
        $url = new urlExt();
        $topic_name = $url->get_param("name");
        $topic = new bookModel();
        $topic_info = $topic->where("name like '%$topic_name%'")->select()->result['data'][0];
        $topic_id = $topic_info->id;
        $category_id = $topic_info->category_id;
        $category_info = $category->where("id = $category_id")->select()->result['data'][0];
        $menu = $category->where("parent_id = {$category_info->parent_id}")->select()->result['data'];
        $chapter = new chapterModel();
        $chapter_info = $chapter->where("book_id = $topic_id and is_article=0")->select()->result['data'];
        $this->data['chapters'] = module::chapter($chapter_info);
        $this->data['menu'] = module::h_menu($menu);
        $this->show("chapter");
    }
}
