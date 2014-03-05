<?php

 class mysqlExt {

    //Mysql 连接资源
    protected $connection;
    //类名
    protected $table_name;
    //结果集对象
    public $result;
    //查询语言
    private $query;
    private $query_count;
    //字段字符串
    private $query_fields;

    public function __construct() {
        $this->connect();
        $this->table_name = $this->get_table_name();
        $this->get_all_fields();
        $this->set_fields_to_attr();
        $this->get_field_str();
        $this->query = "select $this->query_fields from $this->table_name ";
        $this->query_count = "select count(*) as total_num from $this->table_name ";
    }

    private function connect() {
        /* 替换为你自己的数据库名（可从管理中心查看到） */
        $dbname = 'book';

        /* 从环境变量里取出数据库连接需要的参数 */
        $host = "115.28.140.163";
        $port = 3306;
        $user = "cwg";
        $pwd = "acbabc";

        /* 接着调用mysql_connect()连接服务器 */
        $this->connection = @mysql_connect("{$host}:{$port}", $user, $pwd, true);

        mysql_select_db($dbname);
        mysql_set_charset("utf8");
    }

    public function get_table_name() {
        return str_replace("Model", "", get_class($this)) ;
    }

    public function get_mysql_info() {
        $return = array();
        $return['mysql_get_client_info'] = mysql_get_client_info();
        $return['mysql_get_host_info'] = mysql_get_host_info();
        $return['mysql_get_proto_info'] = mysql_get_proto_info();
        $return['mysql_get_server_info'] = mysql_get_server_info();
        $return['mysql_thread_id'] = mysql_thread_id();
        $return['mysql_client_encoding'] = mysql_client_encoding();
        $return['mysql_stat'] = mysql_stat();
        $return['mysql_ping'] = mysql_ping();
        $return['mysql_list_processes'] = mysql_list_processes();
        $this->result['mysql_info'] = $return;
    }

    //获取结果集对象
    public function fetch_object() {
        $return = array();
        if (!empty($this->result['query'])) {
            while ($row = mysql_fetch_object($this->result['query'])) {
                $return[] = $row;
            }
        }
        return $return;
    }

    public function get_field_str() {
        $str = "";
        foreach ($this->result['fields'] as $field) {
            $str .= $field->Field . ",";
        }
        $this->query_fields = trim($str, ",");
    }

    public function get_all_fields() {
        $sql = "desc $this->table_name";
        $query = $this->send_query($sql);
        $this->result['fields'] = $query->result['data'];
        $query->result['data'] = array();
        return $this;
    }

    //发送查询请求
    public function send_query($sql) {
        $result = mysql_query($sql, $this->connection);
        $this->result['query'] = $result;
        $this->result['data'] = $this->fetch_object();
        $this->result['error'] = $this->get_error_info();
        $this->query = "select $this->query_fields from $this->table_name ";
        return $this;
    }

    //将所有的字段赋值给当前类
    public function set_fields_to_attr() {
        foreach ($this->result['fields'] as $field_info) {
            $this->{$field_info->Field} = $field_info->Default;
        }
    }

    //所有的错误信息
    public function get_error_info() {
        //错误文本信息
        $return['error'] = mysql_error();
        //错误数字编码
        $return['errno'] = mysql_errno();
        return $return;
    }

    //返回所有的数据库
    public function get_all_databases() {
        $return = array();
        $db_list = mysql_list_dbs($this->connection);
        while ($db = mysql_fetch_object($db_list)) {
            $return[] = $db->Database;
        }
        return $return;
    }

    public function select() {
        return $this->send_query($this->query);
    }

    public function orderby($order) {
        $this->query .= "order by $order";
        return $this;
    }

    public function where($condition, $select_count = 0) {
        if ($select_count) {
            $this->query = $this->query_count . " where $condition ";
        } else {
            $this->query .= " where $condition ";
        }
        return $this;
    }

    public function get_row_info_by_id($id) {
        $sql = $this->query . "where id=$id";
        $this->send_query($sql);
        return $this->result['data'][0];
    }

    public function limit($limit) {
        $this->query .= " limit $limit ";
        return $this;
    }

    public function get_data() {
        return $this->result['data'];
    }

    public function update() {
        if (!empty($this->data)) {

            $table_name = $this->get_table_name();
            $value_str = "";
            foreach ($this->data as $field => $v) {
                if ($field != $this->update_field) {
                    $values = mysql_real_escape_string($v);
                    $value_str .= "$field = '$values' ,";
                }
            }
            $value_str = trim($value_str, ",");
            $update_key_value = $this->data[$this->update_field];
            $sql = "update $table_name set $value_str where $this->update_field =$update_key_value ";
            return mysql_query($sql, $this->connection);
        }
    }

    public function delete() {
        if (!empty($this->data)) {
            $table_name = $this->get_table_name();
            $sql = "delete from $table_name where {$this->data['field']} = {$this->data['value']}";
            return mysql_query($sql, $this->connection);
        }
    }

    public function save() {
        if (!empty($this->data)) {
            $field = implode(",", array_keys($this->data));
            $value_str = "";
            foreach ($this->data as $key => $values) {
                $values = mysql_real_escape_string($values);
                if ($key != 'content' && $_SERVER['REQUEST_METHOD'] == 'POST') {
                    $values = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
                }
                $value_str .= "'$values',";
            }
            $value_str = trim($value_str, ",");
            $table_name = $this->get_table_name();
            $sql = "insert into  $table_name($field) values($value_str)";

            $q = mysql_query($sql, $this->connection);
            return mysql_insert_id();
        } else {
            $return = 0;
        }
        return $return;
    }

    public function __destruct() {
        
    }

}

?>
