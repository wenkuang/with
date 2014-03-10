<?php

class chapterRoute extends headerRoute{
    
    public function index(){
        include 'modules/index.php';
        
        $url = new urlExt();
        $chapter_name = $url->get_param("name");
        $chapter = new chapterModel();
        $cur_chapter = $chapter->where("title like '%$chapter_name%'")->select()->result['data'][0];
        $chapter_info = $chapter->where("parent_id = {$cur_chapter->id}")->select()->result['data'];
        $this->data['chapters'] = module::chapter($chapter_info);
        
        $book = new bookModel();
        $book_info = $book->where("id={$cur_chapter->book_id}")->select()->result['data'][0];
        $category = new categoryModel();
        $category_info = $category->where("id={$book_info->category_id}")->select()->result['data'][0];
        $menu =  $category->where("parent_id={$category_info->parent_id}")->select()->result['data'];
        $this->data['menu'] = module::h_menu($menu);
        $this->show("chapter");
    }
}
