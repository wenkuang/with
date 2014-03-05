<?php

class categoryRoute extends headerRoute{
    
    public function index(){
        include 'modules/index.php';
        $url = new urlExt();
        $category_name = $url->get_param("name");
        $category = new categoryModel();
        $book = new bookModel();
        $category_info = $category->where("name like '%$category_name%'")->select()->result['data'][0];
        $category_id = $category_info->id;
        $book_list = $book->where("category_id = $category_id")->select()->result['data'];
        $menu = $category->where("1=1")->select()->limit(10)->result['data'];
        $this->data['category'] = module::blocklist($book_list);
        $this->data['menu'] = module::menu($menu);
        $this->show("category");
    }
}
