<?php

class postRoute extends headerRoute{
    
    public function index(){
        include 'modules/index.php';
        $category = new categoryModel();
        $menu = $category->where("1=1")->select()->limit(10)->result['data'];
        $url = new urlExt();
        $post_name = $url->get_param("name");
        $chapter = new chapterModel();
        $chapter_info = $chapter->where("title like '%$post_name%'")->select()->result['data'][0];
        $chapter_id = $chapter_info->id;
        $article = new articleModel();
        $article_info = $article->where("chapter_id = $chapter_id")->select()->result['data'];
        $article_info[0]->name=$post_name;
        $this->data['post'] = module::post($article_info);
        $this->data['menu'] = module::menu($menu);
        $this->show("post");
    }
}
