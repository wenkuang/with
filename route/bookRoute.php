<?php

class bookRoute extends headerRoute{
    
    public function index(){
        $this->data['book'] = "book ";
        $this->data['site_name'] = "China  ";
        $this->data['code'] = "alert(111);  ";
        $this->show("book");
    }
}
