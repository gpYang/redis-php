# RedisModel.php

统一redis的配置信息与数据存储规则，将原生方法封装为更易于使用的类函数，引入表的概念，让使用者更容易理解数据的存储结构

### 字符串 (String)

```php
$config = array(
	'ip' => '127.0.0.1',
	'port' => '6379',
	'passwd' => '123456',
);
$redis_model = new RedisModel($config);
// 保存
$redis_model->setString('captcha', '125864', 60);
// 查询
$captcha = $redis_model->getString('captcha');
// 删除
$redis_model->deleteString('captcha');
```
### 数组 (Array)

```php
$redis_model = new RedisModel();
$config = array(
	'ip' => '127.0.0.1',
	'port' => '6379',
	'passwd' => '123456',
);
$redis_model->connect($config);
$data = array('uid' => 1008, 'name' => '小明');
// 保存
$redis_model->setArray('session_id', $data, 7200);
// 查询
$array = $redis_model->getArray('session_id');
// 删除
$redis_model->deleteArray('session_id');
```

## 表格 (Table)

```php
$redis_model = new RedisModel();
$config = array(
	'ip' => '127.0.0.1',
	'port' => '6379',
	'passwd' => '123456',
);
$redis_model->connect($config);
$data = array('uid' => 1008, 'name' => '小明', 'sex' => 1);
// 保存
$redis_model->setTableRow('users', '1008', $data);
// 查询
$array = $redis_model->getTableRow('users', '1008');
// 删除
$redis_model->deleteTableRow('users', '1008');
```

## 列表 (List)

```php
$redis_model = new RedisModel();
$config = array(
	'ip' => '127.0.0.1',
	'port' => '6379',
	'passwd' => '123456',
);
$redis_model->connect($config);
$data = array('uid' => 1008, 'name' => '小明', 'sex' => 1);
// 入列
$redis_model->pushList('queue_userinfo', '1008', $data);
// 出列 (无数据时等待60秒)
$array = $redis_model->pullList('queue_userinfo', 60);
// 列表长度
$size = $redis_model->getListSize('queue_userinfo');
// 删除
$redis_model->deleteList('queue_userinfo');
```
