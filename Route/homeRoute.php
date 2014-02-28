<?php

class homeRoute extends headerRoute{
    
    public function index(){
        $this->data['fromHome'] = "Yail home ";
        $this->data['site_name'] = "China  ";
        $this->data['c'] = "english  ";
        $this->show("index",  $this->data);
    }
}
