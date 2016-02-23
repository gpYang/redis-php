<?php

/**
 * ------------------------------------------
 * redis 工具箱
 * ------------------------------------------
 *
 * 统一redis的配置与数据存储规范，便于扩展与修改
 * 
 * # redis通常用于热数据与消息列队等场景
 * # list内存储array是采用json格式
 * 
 */

class Redis_toolkit 
{

    protected $redis;                  // redis对象
    protected $ip = '127.0.0.1';       // redis服务器ip地址
    protected $port = '6379';          // redis服务器端口

    public function __construct($config = array()) 
    {
        $this->redis = new Redis();
        empty($config) OR $this->connect($config);
    }

    // 连接redis服务器
    public function connect($config = array())
    {
        $state = $this->redis->connect($config['ip'], $config['port']);
        if($state == false){
            die('redis connect failure');
        }
    }

    // 设置一条String
    public function set_string( $key, $text, $expire=null )
    {
        $key = 'string:' . $key;
        $this->redis->set($key, $text);
        if(!is_null($expire)){
            $this->redis->setTimeout($key, $expire);
        }
    }

    // 获取一条String
    public function get_string( $key )
    {
        $key = 'string:' . $key;
        $text = $this->redis->get($key);
        return empty($text) ? NULL : $text;
    }

    // 删除一条String
    public function delete_string( $key )
    {
        $key = 'string:' . $key;
        $this->redis->del($key);
    }

    // 设置一条array
    public function set_array( $key, $arr, $expire=null )
    {
        $key = 'array:' . $key;
        $this->redis->hMset($key, $arr);
        if(!is_null($expire)){
            $this->redis->setTimeout($key, $expire);
        }
    }

    // 获取一条Arrry
    public function get_array( $key )
    {
        $key = 'array:' . $key;
        $arr = $this->redis->hGetAll($key);
        return empty($arr)?NULL:$arr;
    }

    // 删除一条Array
    public function delete_array( $key ){
        $key = 'array:' . $key;
        $this->redis->del($key);
    }

    // 设置一行数据
    public function set_row( $table, $id, $arr, $expire=null ) 
    {
        $key = $table . ':' . $id;
        $this->redis->hMset($key, $arr);
        if(!is_null($expire)){
            $this->redis->setTimeout($key, $expire);
        }
    }

    // 获取一行数据，$fields可为字符或数组
    public function get_row( $table, $id, $fields=null ) 
    {
        $key = $table . ':' . $id;
        if(is_null($fields)){
            $arr = $this->redis->hGetAll($key);
        }else{
            if(is_array($fields)){
                $arr = $this->redis->hmGet($key, $fields);
            }else{
                $arr = $this->redis->hGet($key, $fields);
            }
        }
        return empty($arr) ? NULL : $arr;
    }

    // 删除一行数据
    public function delete_row( $table, $id )
    {
        $key = $table . ':' . $id;
        $this->redis->del($key);
    }

    // 推送数据给列表，头部
    public function push_list( $key, $arr ) 
    {
        $key = 'list:' . $key;
        $this->redis->lPush($key, json_encode($arr));
    }

    // 从列表拉取一条数据，尾部
    public function pull_list( $key, $timeout=0 )
    {
        $key = 'list:' . $key;
        if($timeout > 0){
            $val = $this->redis->brPop($key, $timeout);  // 该函数返回的是一个数组, 0=key 1=value
        }else{
            $val = $this->redis->rPop($key);
        }
        $val = is_array($val) && isset($val[1]) ? $val[1] : $val;
        return empty($val) ? null : $this->object_to_array(json_decode($val));
    }

    // 取得列表的数据总条数
    public function get_list_size( $key )
    {
        $key = 'list:' . $key;
        return $this->redis->lSize($key);
    }

    // 删除列表
    public function delete_list( $key )
    {
        $key = 'list:' . $key;
        $this->redis->del($key);
    }

    // 使用递归，将stdClass转array
    protected function object_to_array( $obj )
    {
        if(is_object($obj)){
            $arr = (array)$obj;
        }
        if(is_array($obj)){
            foreach ($obj as $key => $value) {
                $arr[$key] = $this->object_to_array($value);
            }
        }
        return !isset($arr) ? $obj : $arr;
    }

}
