<?php

class oufei_page{
    private $from;
    public function __construct() {
        $this->from = oufei_config::$form;
        global $db_host;
        global $db_host_r;
        global $db_user;
        global $db_pwd;
        global $db_name;
        global $db_charset;
        $this->db = new CMysql($db_host, $db_host_r, $db_user, $db_pwd, $db_name, $db_charset);
    }
    
    public function get_orders(){
        $sql = "SELECT uid,fee,mobile,pay_time,status FROM  `ota_huiyuan_order`  where  `from`='$this->from' ";
        $data = $this->db->getData($sql);
        return $data;
    }
    
}