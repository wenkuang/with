<?php

class loginRoute extends headerRoute{
    
    public function index(){
        include 'modules/index.php';
echo 5555;
        
        $this->show("login");
    }
}
