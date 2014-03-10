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
        $child_cat = $category->where("parent_id=$category_id")->select()->result['data'];
        $book_list = $book->where("category_id = $category_id")->select()->result['data'];
        if(empty($child_cat)){
            $child_cat = $category->where("parent_id={$category_info->parent_id}")->select()->result['data'];
        }
        $this->data['category'] = module::blocklist($book_list);
        $this->data['menu'] = module::h_menu($child_cat);
        $this->show("category");
    }
}
