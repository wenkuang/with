<?php

class postRoute extends headerRoute{
    
    public function index(){
        include 'modules/index.php';

        $url = new urlExt();
        $post_name = $url->get_param("name");
        $chapter = new chapterModel();
        $chapter_info = $chapter->where("title like '%$post_name%'")->select()->result['data'][0];
        $chapter_id = $chapter_info->id;
        $book_id = $chapter_info->book_id;
        $article = new articleModel();
        $article_info = $article->where("chapter_id = $chapter_id")->select()->result['data'];
        $article_info[0]->name=$post_name;
        $this->data['post'] = module::post($article_info);
        
        $book = new bookModel();
        $book_info = $book->where("id=$book_id")->select()->result['data'][0];
        $category = new categoryModel();
        $category_info = $category->where("id={$book_info->category_id}")->select()->result['data'][0];
        $menu =  $category->where("parent_id={$category_info->parent_id}")->select()->result['data'];
        $this->data['menu'] = module::h_menu($menu);
        
        $this->show("post");
    }
}
